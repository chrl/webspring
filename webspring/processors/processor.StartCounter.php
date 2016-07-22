<?php

    /**
     * StartCounterProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class StartCounterProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * StartCounterProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    $core->getRequest()->set('counter',microtime(true));
	    return array('ok');
	}
    }