<?php

    /**
     * GetSessionProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetCatchDataProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * GetSessionProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
    	public function run($data, CoreInterface $core)
    	{
    	   $data = array();
           
           $catchForm = str_replace('form','',$core->getRequest()->get('entity'));
           
           foreach(json_decode(stripslashes($core->getRequest()->get('data')),true) as $key=>$value) {
                if (strpos($value['id'],$catchForm.'_')===0) {
                    $data[str_replace($catchForm.'_','',$value['id'])] = $value['value'];
                }
           }
           
           return array('ok',array('data'=>$data));
    	}
    }