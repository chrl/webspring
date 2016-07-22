<?php

    interface ModuleInterface
    {
	/**
	 * intro()
	 * 
	 * @param mixed $core
	 * @return
	 */
	public function intro(CoreInterface $core);
	
	/**
	 * setModuleConfig()
	 * 
	 * @param mixed $config
	 * @return
	 */
	public function setModuleConfig(array $config);
	
	/**
	 * outro()
	 * 
	 * @param mixed $core
	 * @return
	 */
	public function outro(CoreInterface $core);

    }