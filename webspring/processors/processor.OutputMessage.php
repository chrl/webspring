<?php

    /**
     * OutputMessageProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class OutputMessageProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * OutputMessageProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    if (isset($data['message'])) {
		echo $data['message'].'<br />';
	    } elseif ($core->getRequest()->get('message')) {
		echo $core->getRequest()->get('message').'<br />';
	    }
	    return array('ok');
	}
    }