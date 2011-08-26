<?php

    /**
     * OutputCounterProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class OutputCounterProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * OutputCounterProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    echo 'Execution took: '.(microtime(true)-$core->getRequest()->get('counter')).'<br />';
	    return array('ok');
	}
    }