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

            $this->core->getLogger()->log('request: '.var_export($request,true));
    	    
    	    
    	    foreach ($this->core->getConfig()->get('execution') as $pathName=>$path) {
    		$this->core->getLogger()->log('Checking path2: '.$pathName);
    		$suits = true;
    		
    		if (isset($path['condition']['request'])) {
    		    foreach ($path['condition']['request'] as $param=>$value) {
    
    		    
    		    if ($request->get($param)) {
    			
    			if ($value == false) {
    	    		    $this->core->getLogger()->log('Param "'.$param.'" value ('.var_export($request->get($param),true).') exists, continuing...');
    		}
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
    
    		}

			if (isset($path['condition']['uri'])) {

                $this->core->getLogger()->log('Got uri: '.$_SERVER['REQUEST_URI']);
                $uri = $_SERVER['REQUEST_URI'];


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

			}
    		
    		
    		if ($suits) {
    		    return $pathName;
    		}
    		
    	    }
    	    
    	    return 'default-path';
    	}

    }