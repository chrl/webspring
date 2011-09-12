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
    class GetSessionProcessor extends BaseProcessor implements ProcessorInterface
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
           if (isset($_SESSION['user'])) {
                return array('ok',array('user'=>$_SESSION['user']));
           } else {
                return array('fail');
           }
           
    	}
    }