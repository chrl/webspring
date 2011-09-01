<?php

    /**
     * WebCore
     * 
     * Web implementation of WebSpring core
     * 
     * @see CoreInterface
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @todo fix spaces->tabs
     * @access public
     */
    class WebCore implements CoreInterface
    {
    	protected $config = array();
    	protected $request = null;
    	
    	protected $modules = array();
    	protected $attachedModules = array();
    	
    	protected $logger = null;
        protected $debugLevel = null;
    	
    	/**
    	 * WebCore::process()
    	 * Main core process
         * 
    	 * @return CoreInterface $this
    	 */
            
    	public function process()
    	{
    	    $this->setRequest(new WebRequest());
    	    
    	    $this->getLogger()->setLevel(
                                                ! is_null($this->debugLevel)
                                                ? $this->debugLevel 
                                                : $this->
                                                         getConfig()->
                                                         get('settings.debug')
                                    );
                
    	    $this->getLogger()->log('Started processing request');
    	    
    	    $path = $this->router->resolveProcessingPath();
                
    	    $this->getLogger()->log('Resolved processing path: '.$path);
            
            $this->getRequest()->set('path',$path);
    	    
    	    $path = $this->getConfig()->get('execution.'.$path);
            
                if (isset($path['set'])) {
                    $this->getLogger()->log('Filling path params');
                    
                    foreach ($path['set'] as $key=>$value)
                    {
                        $this->getRequest()->set($key,$value);
                        $this->getLogger()->log('Setting param "'.$key.'" to '.$value);
                    }    
                }
            
    	    
    	    $this->getLogger()->log('Executing path');
    	    
    	    $this->executePath($path['tree']);
    	    
            return $this;
    	}
        
        public function executeIncludePath($path)
        {
   	        $this->getLogger()->log('Executing include path "'.$path.'"');
            $path = $this->getConfig()->get('include.'.$path);
            if ($path) {
                
                if (isset($path['set'])) {
                    $this->getLogger()->log('Filling path params');
                    
                    foreach ($path['set'] as $key=>$value)
                    {
                        $this->getRequest()->set($key,$value);
                        $this->getLogger()->log('Setting param "'.$key.'" to '.$value);
                    }    
                }
                
        	    $this->executePath($path['tree']);                

                
            } else $this->getLogger()->log('Include path not found in config');
            return $this;
        }
        
        public function setDebugLevel($level)
        {
            $this->debugLevel = $level;
            return $this;
        }
        
        public function getDebugLevel()
        {
            return $this->debugLevel;
        }
	
    	/**
    	 * WebCore::shutdown()
             * Gracefully shuts down all modules
             * During shutdown in Module::outro() method you can i.e. close connections
             * to databases or output resulting messages or close files.
             * Shutdown method calls modules in order of their appearance in config.
    	 * 
    	 * @return CoreInterface $this
    	 */
    	public function shutdown()
    	{
    	    $this->getLogger()->log('Gracefully shutting down - calling all outro methods');
    	    
    	    foreach( $this->getModules() as $moduleName=>$module)
    	    {
    		$module->outro($this);
    	    }
                return $this;
    	}
    	
    	/**
    	 * WebCore::executeProcessor()
             * Execute processor and return execution result.
             * Method calls all "preexecute" methods of attached modules before
             * execution, and calls "postexecute" after it's execution.
             * 
             * Module can forbid execution of processor if sends "skip" as one of
             * it's result actions.
             * 
             * @see BaseModule::intro()
             *  
    	 * @param string $handler Name of the handler to execute
    	 * @param mixed $data Data to pass to handler, set in config
    	 * @return Array Result of processor execution
    	 */
    	protected function executeProcessor($handler,$data)
    	{
    	    if (!$handler) return array('no-processor');
            
            $this->getLogger()->log('Locating processor: '.$handler);
    	    $this->getLogger()->log('Got '.$handler.'Processor data: '.var_export($data,true));
    	    $actions = array();
    	    
    	    if(isset($this->attachedModules[$handler])) foreach($this->attachedModules[$handler] as $moduleName=>$module)
    	    {
        		if(method_exists($module,'preexecute')) {
        		    $actions[$moduleName] = $module->preexecute($handler,$data,$this);
        		}
    	    }
    	    
    	    $skip = false;
    	    $result = false;
    	    
    	    foreach ($actions as $moduleActions) {
    
        		if (array_key_exists('skip',$moduleActions)) {
        		    $result = $moduleActions['skip'];
        		    $skip = true;
        		    break;
        		}
    	    }
    	    
    	    if (!$skip) {
        	        
        		$this->getLogger()->log('Executing processor: '.$handler);
        
        		$handle = $handler.'Processor';	    
        		$processor = new $handle();
        		$result = $processor->run($data,$this);
        		
        		$this->getLogger()->log('Got execution result: '.var_export($result,true));
    
        		if (count($result)>1) {
        		    $this->getLogger()->log('Batch-setting data: '.var_export($result[1],true));
        		    $this->getRequest()->batchSet($result[1]);
        		}              	    
                
        		if(isset($this->attachedModules[$handler])) foreach($this->attachedModules[$handler] as $moduleName=>$module)
        		{
        		    if(method_exists($module,'postexecute')) {
        			     $module->postexecute($handler,$data,$result,$this);			
        		    }
        		}
    	    } elseif (count($result)>1) {
    		    $this->getLogger()->log('Batch-setting data: '.var_export($result[1],true));
    		    $this->getRequest()->batchSet($result[1]);
       		}              	    
    	    
    	    return $result;
    	}
	
    	/**
    	 * WebCore::executePath()
         * 
         * Method traverses the tree of execution and executes processors
         * in order of appearence in the tree. Method can jump to another path
         * if it is defined as string. Method recursion helps to traverse
         * all the tree, parsing path as execution continues.
    	 * 
    	 * @param array $path Execution path
    	 * @return CoreInterface $this
    	 */
    	protected function executePath($path)
    	{
    	    if (is_array($path)) {
    		list($processor) = array_keys($path);
    
    		$action = $this->executeProcessor(
    			$processor,
    			isset($path[$processor]['data'])
    			    ? $path[$processor]['data']
    			    : array()
    		);
    		
    		$action = $action[0];
    		
    		$this->getLogger()->log('Got '.$processor.'Processor return action: '.$action);
    		
    		if (isset($path[$processor][$action])) {
    		    $this->executePath($path[$processor][$action]);
    		}
    		
    	    } else {
        		$jumpPath = $this->getConfig()->get('execution.'.$path);
        		$this->getLogger()->log('Jumping to path: '.$path);
                
                if (isset($jumpPath['set'])) {
                    $this->getLogger()->log('Filling path params');
                    
                    foreach ($jumpPath['set'] as $key=>$value)
                    {
                        $this->getRequest()->set($key,$value);
                        $this->getLogger()->log('Setting param "'.$key.'" to '.$value);
                    }    
                }

        		$this->executePath($jumpPath['tree']);
    	    }
    	    
                return $this;
    	}
	
	/**
	 * WebCore::setRequest()
	 * Request setter method
         * 
	 * @param RequestInterface $request
	 * @return CoreInterface $this
	 */
	protected function setRequest(RequestInterface $request)
	{
	    $this->request = $request;
	    return $this;
	}
	
	/**
	 * WebCore::setConfig()
     * Config setter method
	 * 
	 * @param ConfigInterface $config
	 * @return CoreInterface $this
	 */
	public function setConfig(ConfigInterface $config)
	{
	    $this->config = $config;
	    return $this;
	}
    
	/**
	 * WebCore::setRouter()
     * Router setter method
	 * 
	 * @param RouterInterface $router
	 * @return CoreInterface $this
	 */
	public function setRouter(RouterInterface $router)
	{
	    $this->router = $router->linkCore($this);
	    return $this;
	}    
	
	/**
	 * WebCore::setLogger()
     * Logger setter method
	 * 
	 * @param LoggerInterface $logger
	 * @return CoreInterface $this
	 */
	public function setLogger(LoggerInterface $logger)
	{
	    $this->logger = $logger;
	    return $this;
	}
	
	/**
	 * WebCore::getLogger()
         * Logger getter
         * 
         * Simple lazy initialize method - initializes logger if not
         * inited already.
	 * 
	 * @return LoggerInterface
	 */
	public function getLogger()
	{
	    if (!$this->logger) {
                
                $engine = $this->getConfig()->get('settings.logengine');
                if (!$engine) $engine = 'ScreenLogger';
                $this->setLogger(new $engine($this));
            }
	    return $this->logger;
	}
	
	/**
	 * WebCore::getRequest()
         * Request getter
	 * 
	 * @return RequestInterface
	 */
	public function getRequest()
	{
	    return $this->request;
	}
	
	/**
	 * WebCore::getConfig()
         * Config getter
	 * 
	 * @return ConfigInterface
	 */
	public function getConfig()
	{
	    return $this->config;
	}
	
	/**
	 * WebCore::attach()
         * 
         * Attach module to core process
         * 
         * Simple singleton implementation - one module doesn't attaches twice.
         * Calls module intro() function when attached.
         * Modules are attached in order of their appearence in program.
	 * 
	 * @param string $module Name of the module to attach
	 * @return CoreInterface $this
	 */
	public function attach($module)
	{
	    $this->getLogger()->log('Attaching module '.$module.'...');
	    
	    if (!isset($this->modules[$module])) {
                
                $mod = $module.'Module';
		$mod = new $mod();
		$mod->setModuleConfig(array($module=>$this->getConfig()->get('modules.'.$module)))->intro($this);
		$this->modules[$module] = $mod;
	    }
	    
	    return $this;
	}
        
	/**
	 * WebCore::attachModuleToHandler()
         * 
         * Attaches post-execute and pre-execute methods of module to handler.
         * Methods are executed when processor is being prepared to execution and
         * right after processor's execution.
         * 
         * @see ModuleInterface::postexecute()
         * @see ModuleInterface::preexecute()
	 * 
	 * @param mixed $handlerName Handler name
	 * @param mixed $moduleName Module Name
	 * @param ModuleInterface $module Module object to attach. Name given in
         * order to give ability to override modules.
	 * @return CoreInterface $this
	 */
	public function attachModuleToHandler($handlerName,$moduleName,  ModuleInterface $module)
	{
	    $this->attachedModules[$handlerName][$moduleName] = $module;
	    return $this;
	}
	
	/**
	 * WebCore::getModules()
	 * 
         * Gets list of attached modules
         * 
	 * @return array $modules
	 */
	protected function getModules()
	{
	    return $this->modules;
	}
	
	/**
	 * WebCore::getModule()
         * 
         * Gets attached module by it's name
	 * 
         * @access public
	 * @param string $moduleName Name of wished module
	 * @return ModuleInterface $module
	 */
	public function getModule($moduleName)
	{
	    if (!isset($this->modules[$moduleName])) {
	       $this->getLogger()->log('Tried to get module '.$moduleName.', lazy loading');
	       $this->attach($moduleName);
	    }
        
            
        return $this->modules[$moduleName];
	}
	
}