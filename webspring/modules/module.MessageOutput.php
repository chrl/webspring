<?php

    /**
     * MessageOutputModule
     * 
     * @package Webspring  
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    class MessageOutputModule extends BaseModule implements ModuleInterface
    {
	
    	/**
    	 * MessageOutputModule::outro()
    	 * 
    	 * @param mixed $core
    	 * @return
    	 */
    	public function outro(CoreInterface $core) {
    	    
    	    if ($message = $core->getRequest()->get('message')) {
    		
        		$core->getLogger()->log('Got message: '.$message);
        		
        		foreach($core->getRequest()->getAll() as $key=>$value) {
        		    $message = str_replace('%'.$key.'%',$core->getRequest()->get($key),$message);
        		}
                
        		$core->getLogger()->log('Output message: '.$message);
        		echo $message;
    	    }
    	}
    }