<?php

    /**
     * TemplateOutputModule
     * 
     * @package Webspring  
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    class TemplateOutputModule extends BaseModule implements ModuleInterface
    {
    	/**
    	 * TemplateOutputModule::outro()
    	 * 
    	 * @param CoreInterface $core
    	 * @return
    	 */
    	public function outro(CoreInterface $core) {
    	   
           $this->core = $core;
    	    
    	    if ($template = $core->getRequest()->get('template')) {
        		$core->getLogger()->log('Parsing template: '.$template);
                $this->fetchtemplate()->assembly()->flush();
    	    }
    	}
        
        private function preloadtemplate($tpl)
        {
     		if (!file_exists($this->core->getConfig()->get('settings.templatedir'). $tpl.'.tpl'))
            die('<b>Fatal:</b> SubTemplate doesn\'t exist: '.$tpl);
            
            $this->core->getLogger()->log('Fetching subtemplate: '.$tpl.'.tpl');
            
    		return file_get_contents($this->core->getConfig()->get('settings.templatedir'). $tpl.'.tpl');
        }        
        
    	private function fetchtemplate()
    	{
     		
            if (!file_exists($this->core->getConfig()->get('settings.templatedir') . $this->core->getRequest()->get('template')))
            throw new Exception('Template doesn\'t exist: '.$this->core->getRequest()->get('template'));
            
    		$this->template = file_get_contents($this->core->getConfig()->get('settings.templatedir') . $this->core->getRequest()->get('template'));
            
            // parsing all template includes
            
        	preg_match_all('/\%i\:(.+)\%/iU', $this->template, $matches);
            
            while (count($matches[0])>0) {

        		for ($i = 0; $i < count($matches[0]); ++$i)
                {
                    $this->core->executeIncludePath($matches[1][$i]);
                    $this->template = str_replace($matches[0][$i],
                         '',
                         $this->template);
                }
            	preg_match_all('/\%i\:(.+)\%/iU', $this->template, $matches);
                
            }
            
            
            
            
            
            
            // running n times - to parse second level templates
            
        	preg_match_all('/\%p\:(.+)\%/iU', $this->template, $matches);
            
            while (count($matches[0])>0) {

        		for ($i = 0; $i < count($matches[0]); ++$i)
                {
                    $this->template = str_replace($matches[0][$i],
                         $this->preloadtemplate($matches[1][$i]),
                         $this->template);
                }
            	preg_match_all('/\%p\:(.+)\%/iU', $this->template, $matches);
                
            }
            
            return $this;
    	}
        
        private function assembly()
        {
            foreach($this->core->getRequest()->getAll() as $key=>$value)
            {
                if (is_array($value))
                {
                    foreach ($value as $param=>$paramValue) {
                        $this->template = str_replace('%'.$key.'.'.$param.'%',$paramValue,$this->template);
                    }
                    
                } else $this->template = str_replace('%'.$key.'%',$value,$this->template);
            }
            return $this;
        }
        
        private function flush()
        {
            echo $this->template;
        }        
    }