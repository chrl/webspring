<?php
    class RequestRouter extends AbstractRouter implements RouterInterface
    {
       	/**
    	 * RequestRouter::resolveProcessingPath()
         * 
         * Parses request and returns execution path name
         * Search commited by search conditions,
         * @see ConfigInterface
         * 
         * If path not found by conditions, returns 'default-path' 
    	 * 
    	 * @return string Execution path
    	 */
    	public function resolveProcessingPath()
    	{
    	    $request = $this->core->getRequest();
    	    
    	    
    	    foreach ($this->core->getConfig()->get('execution') as $pathName=>$path) {
    		$this->core->getLogger()->log('Checking path: '.$pathName);
    		$suits = true;
    		
    		if (isset($path['condition']['request'])) foreach ($path['condition']['request'] as $param=>$value) {
    
    		    
    		    if ($request->get($param)) {
    			
    			if ($value == false) {
    	    		    $this->core->getLogger()->log('Param "'.$param.'" value ('.var_export($request->get($param),true).') exists, continuing...');
    			    continue;
    			}
    			if ($value == $request->get($param)) {
            		    $this->core->getLogger()->log('Param "'.$param.'" value ('.var_export($request->get($param),true).') is equal to required ('.var_export($value,true).'), continuing...');
    			    continue;
    			}
    			if (($value[0]=='/') && preg_match($value, $request->get($param))) {
            		    $this->core->getLogger()->log('Param "'.$param.'" value ('.var_export($request->get($param),true).') matches required pattern ('.var_export($value,true).'), continuing...');
    			    continue;
    			}
    		    }
    		    
    		    $this->core->getLogger()->log('Param "'.$param.'" value ('.var_export($request->get($param),true).') doesn\'t match required ('.var_export($value,true).'), path check failed');
    			
    		    $suits = false;
    		    break;
    
    		} else {
    		    $suits = false;
    		}
    		
    		
    		if ($suits) return $pathName;
    		
    	    }
    	    
    	    return 'default-path';
    	}

    }