<?php

    interface ProcessorInterface
    {
	/**
	 * run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core);
    }