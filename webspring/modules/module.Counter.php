<?php

    /**
     * CounterModule
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class CounterModule extends BaseModule implements ModuleInterface
    {
	protected $counter = 0;
	/**
	 * CounterModule::intro()
	 * 
	 * @param CoreInterface $core
	 * @return
	 */
	public function intro(CoreInterface $core) {
	    $this->counter = microtime(true);
	    return $this;
	}
	
	/**
	 * CounterModule::outro()
	 * 
	 * @param CoreInterface $core
	 * @return
	 */
	public function outro(CoreInterface $core) {
	    $core->getLogger()->log('Execution took: '.sprintf('%0.1f',1000*(microtime(true)-$this->counter)).'msec');
	}
    }