<?php

    /**
     * CacheModule
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class CacheModule extends BaseModule implements ModuleInterface
    {

    	public function intro(CoreInterface $core)
        {
    	    if (!isset($this->config['activeHandlers']) || !is_array($this->config['activeHandlers'])) return $this;
    	    
            $core->getLogger()->log('Attaching module '.$this->name.' to handlers: '.implode(', ',array_keys($this->config['activeHandlers'])));
    	    foreach ($this->config['activeHandlers'] as $handler=>$options) {
    		  $core->attachModuleToHandler($handler,$this->name,$this);
    	    }
            
            $this->storage = new $this->config['handler'];
            $this->storage->linkCore($core);
 
    	    return $this;
            
        }
        
        
        /**
    	 * CacheModule::getKey()
    	 * 
    	 * @param mixed $handler
    	 * @param mixed $params
    	 * @return
    	 */
    	protected function getKey($handler,$params)
    	{
    	    return md5('cache'.$handler.serialize($params));
    	}
    	
    	/**
    	 * CacheModule::preexecute()
    	 * 
    	 * @param mixed $handler
    	 * @param mixed $data
    	 * @param mixed $core
    	 * @return
    	 */
    	public function preexecute($handler, $data, CoreInterface $core)
    	{
    
    	    $params = array();
    	    
    	    if (isset($data['input'])) foreach ($data['input'] as $param) $params[$param] = $core->getRequest()->get($param);
    	    
    	    $key = $this->getKey($handler,$params);
            
    	    $core->getLogger()->log('Trying to load '.$handler.' output data from cache using '.$this->config['handler']);
            
            $this->storage->setExpireTime($this->config['activeHandlers'][$handler]);
    	    
            $cached = $this->storage->get($handler.'.'.$key);
            
            $core->getLogger()->log(
                $cached
                    ? 'Got cached copy of processor '.$handler
                    : 'Got no cached copy of processor '.$handler
            );
            
    	    return $cached 
                        ? array('skip'=>$cached)
                        : array('no-cache-record');
    	}
    
    	/**
    	 * CacheModule::postexecute()
    	 * 
    	 * @param mixed $handler
    	 * @param mixed $data
    	 * @param mixed $result
    	 * @param mixed $core
    	 * @return
    	 */
    	public function postexecute($handler, $data, $result, CoreInterface $core)
    	{
    	    
    	    $params = array();
    	    
    	    if (isset($data['input'])) foreach ($data['input'] as $param) $params[$param] = $core->getRequest()->get($param);
    	    
    	    $key = $this->getKey($handler,$params);
            
    	    if (isset($data['output'])) {
    		
                if (!isset($result[1])) $result[1]=array();
        		foreach ($data['output'] as $param)
        		{
        		    $p = $core->getRequest()->get($param);
        		    $result[1][$param]=$p;
        		}
    	    }
            
            
    	    $core->getLogger()->log('Saving '.$handler.' output data to cache using '.$this->config['handler']);
            $this->storage->setExpireTime($this->config['activeHandlers'][$handler]);
            $this->storage->set($handler.'.'.$key,$result);
    	    
    	    return $this;	    
    	    
    	}	
	
    }