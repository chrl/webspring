<?php

    /**
     * WebRequest
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class WebRequest extends AbstractStorage implements RequestInterface, StorageInterface
    {
	/**
	 * WebRequest::__construct()
	 * 
	 * @return
	 */
	public function __construct()
	{
	    $this->batchSet($_REQUEST);
	    return $this;
	}
    }