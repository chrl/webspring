<?php

    /**
     * SetRequestProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class SetRequestProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * SetRequestProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    return array('ok',$data);
	}
    }