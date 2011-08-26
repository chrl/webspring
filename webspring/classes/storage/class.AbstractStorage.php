<?php

    /**
     * AbstractStorage
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    abstract class AbstractStorage implements StorageInterface
    {
	protected $storage = array();
	
	/**
	 * AbstractStorage::get()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public function get($key)
	{
	    return isset($this->storage[$key])
		    ? $this->storage[$key]
		    : false;
	}
	/**
	 * AbstractStorage::set()
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	public function set($key,$value)
	{
	    $this->storage[$key] = $value;
	    return $this;
	}
	
	/**
	 * AbstractStorage::batchSet()
	 * 
	 * @param mixed $values
	 * @return
	 */
	public function batchSet(array $values)
	{
	    foreach ($values as $key=>$value) {
		$this->storage[$key] = $value;
	    }
	    return $this;
	}
	/**
	 * AbstractStorage::getAll()
	 * 
	 * @return
	 */
	public function getAll()
	{
	    return $this->storage;
	}
    }