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
    class RedirectProcessor extends BaseProcessor implements ProcessorInterface
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
           header('Location: '.$data['path']);
           return array('ok');
    	}
    }