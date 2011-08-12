<?php

    interface StorageInterface
    {
	/**
	 * get()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public function get($key);
	/**
	 * set()
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @return
	 */
	public function set($key,$value);
	/**
	 * batchSet()
	 * 
	 * @param mixed $values
	 * @return
	 */
	public function batchSet(array $values);
    }