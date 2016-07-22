<?php
    
    function __autoload($className)
    {
        
        
        $startDir = dirname(__FILE__).'/';
        $startCurrentDir = getcwd().'/../';
        
	
    	$route = array(
                
                // core
                
    	    'WebCore'=>'core',
    	    'WebRequest'=>'io',
                
                // loggers
                
    	    'Logger'=>'logger',
                'ScreenLogger'=>'logger',
                'ConsoleLogger'=>'logger',
                'FileLogger'=>'logger',
                
                // database layer
                'PgsqlDatasource'=>'db',
                'MysqlDatasource'=>'db',
                'DatasourceAbstract'=>'db',
                
                // routers

                'AbstractRouter'=>'router',
                'RequestRouter'=>'router',
                'UrlRouter'=>'router',
                
                // cache
                
                'FileCacheHandler'=>'cache',
                'MemoryCacheHandler'=>'cache',
                
                // storage
                
                'AbstractStorage'=>'storage',
                
                // config readers
                
                'Config'=>'config',
    	);
	
    	$additionalPath = array_key_exists($className, $route) 
    		? $route[$className].'/'
    		: '';
	

        if (false !== stripos($className, 'interface')) {
	    
            if (file_exists($startDir.'interfaces/'.$additionalPath.'interface.'.$className.'.php')) {
		
                require_once $startDir.'interfaces/'.$additionalPath.'interface.'.$className.'.php';
                return true;
            }
        }
        
    	if (false !== stripos($className, 'processor')) {

                if (file_exists($startCurrentDir.'processors/'.$additionalPath.'processor.'.str_ireplace('processor','',$className).'.php')) {
    		
                    require_once $startCurrentDir.'processors/'.$additionalPath.'processor.'.str_ireplace('processor','',$className).'.php';
                    return true;
                }
    	    
                if (file_exists($startDir.'processors/'.$additionalPath.'processor.'.str_ireplace('processor','',$className).'.php')) {
    		
                    require_once $startDir.'processors/'.$additionalPath.'processor.'.str_ireplace('processor','',$className).'.php';
                    return true;
                }
            }
            
    	if (false !== stripos($className, 'entity')) {
    	   
                if (file_exists($startCurrentDir.'entity/'.$additionalPath.'entity.'.str_ireplace('entity','',$className).'.php')) {
    		
                    require_once $startCurrentDir.'entity/'.$additionalPath.'entity.'.str_ireplace('entity','',$className).'.php';
                    return true;
                }
                    	    
                if (file_exists($startDir.'entity/'.$additionalPath.'entity.'.str_ireplace('entity','',$className).'.php')) {
    		
                    require_once $startDir.'entity/'.$additionalPath.'entity.'.str_ireplace('entity','',$className).'.php';
                    return true;
                }
            }        
    	
    	if (false !== stripos($className, 'module')) {
    	    
                if (file_exists($startDir.'modules/'.$additionalPath.'module.'.str_ireplace('module','',$className).'.php')) {
    		
                    require_once $startDir.'modules/'.$additionalPath.'module.'.str_ireplace('module','',$className).'.php';
                    return true;
                }
            }
    	
    	if (file_exists($startDir.'classes/'.$additionalPath.'class.'.$className.'.php')) {
    	    
    	    require_once $startDir.'classes/'.$additionalPath.'class.'.$className.'.php';
    	    return true;
    	}
	
        return false;
    }
