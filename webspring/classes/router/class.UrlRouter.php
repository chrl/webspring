<?php
    class UrlRouter extends AbstractRouter implements RouterInterface
    {
       	/**
    	 * UrlRouter::resolveProcessingPath()
         * 
         * Parses request uri and returns execution path name
         * Search commited by uri defined in 
         * 
         * If path not found by conditions, returns 'default-path' 
    	 * 
    	 * @return string Execution path
    	 */
    	public function resolveProcessingPath()
    	{
    	    $request = $this->core->getRequest();
            
            $this->core->getLogger()->log('Got uri: '.$_SERVER['REQUEST_URI']);
            $uri = $_SERVER['REQUEST_URI'];
    	    
    	    foreach ($this->core->getConfig()->get('execution') as $pathName=>$path) {
            
    		$this->core->getLogger()->log('Checking path: '.$pathName);
    		$suits = true;
    		
    		if (isset($path['condition']['uri'])) {
    		  
              if (preg_match($path['condition']['uri'],$uri,$matches)) {
                $this->core->getLogger()->log('Match found in path '.$pathName);
                foreach($matches as $key=>$match) {
                    
                    if (isset($path['condition']['match'][$key])) {
                        $this->core->getLogger()->log('Setting param "'.$path['condition']['match'][$key].'" to '.$match);
                        $this->core->getRequest()->set($path['condition']['match'][$key],$match);
                    }   
                }
                
              } else {
                $suits = false;
                $this->core->getLogger()->log($pathName.' mismatched by pattern ('.$path['condition']['uri'].') with uri '.$uri);
              }
                
    		} else {
                $this->core->getLogger()->log('In path '.$pathName.' no uri block found');
    		    $suits = false;
    		}
    		
    		
    		if ($suits) return $pathName;
    		
    	    }
    	    
    	    return 'default-path';
    	}

    }