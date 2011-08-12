<?php

    /**
     * BaseProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    abstract class BaseProcessor implements ProcessorInterface
    {
	/**
	 * BaseProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    throw new Exception('Function RUN must be implemented in '.__CLASS__);
	}
    }