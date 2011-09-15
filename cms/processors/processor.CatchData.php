<?php

    /**
     * CatchDataProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class CatchDataProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * CatchDataProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
    	public function run($data, CoreInterface $core)
    	{
    	    $catchable = $core->getRequest()->get('entity');
            $message = array('result'=>'check','msg'=>'Check entered data please!');
            
            $validator = new Validator($catchable);
            $return = $validator->linkCore($core)->check($core->getRequest()->get('data'));
            
            return array($return['result'],array('result'=>$return));
    	}
    }