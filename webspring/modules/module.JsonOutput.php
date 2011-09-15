<?php

    /**
     * JsonOutputModule
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class JsonOutputModule extends BaseModule implements ModuleInterface
    {
	
    	/**
    	 * JsonOutputModule::outro()
             * 
             * Jsonify "result" request variable
    	 * 
    	 * @param mixed $core
    	 * @return void
    	 */
    	public function outro(CoreInterface $core) {
    	    if ($result = $core->getRequest()->get('result')) {
        		$core->getLogger()->log('Jsonify "result"...');
        		echo json_encode($result);
    	    }
    	}
    }