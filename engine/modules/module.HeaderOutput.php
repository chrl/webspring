<?php

    /**
     * HeaderOutputModule
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class HeaderOutputModule extends BaseModule implements ModuleInterface
    {
	
	/**
	 * HeaderOutputModule::outro()
         * 
         * Output one or more headers from "result" headers variable
	 * 
	 * @param mixed $core
	 * @return void
	 */
	public function intro(CoreInterface $core) {
	    
	    if ($headers = $core->getConfig()->get('settings.headers')) {
                
                foreach($headers as $key=>$value)
                {
                    header($key.': '.$value);
                }
	    }
	}
    }