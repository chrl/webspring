<?php

    /**
     * CheckInputParamsProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class CheckInputParamsProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * CheckInputParamsProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    
	    $params = $core->getRequest()->get('test2');
	    
	    if ($params == 'test3') {
		return array('success');
	    } else {
		return array('fail',array('message'=>'Param found, but value "%test2%" is not equal to "test3"'));
	    }
	}
    }