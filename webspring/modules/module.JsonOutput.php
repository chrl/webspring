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
			$result = $core->getRequest()->get('result');
    	    if (false!== $result) {
        		$core->getLogger()->log('Jsonify "result"...');
        		echo json_encode($result);
    	    }

			if (false!== $core->getRequest()->get('template')) {
				$core->getModule('TemplateOutput')->outro($core);
			}
    	}
    }