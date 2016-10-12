<?php

    /**
     * BaseModule
     * 
     * @package Webspring
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    abstract class BaseModule extends Linkable implements ModuleInterface, LinkableInterface
    {
	
    public $config = array();
    protected $name = 'Base';
	
    /**
     * BaseModule::intro()
     * 
     * @param mixed $core
     * @return
     */
    public function intro(CoreInterface $core)
    {
        if (!isset($this->config['activeHandlers']) || !is_array($this->config['activeHandlers'])) {
            return $this;
        }
        $core->getLogger()->log('Attaching module '.$this->name.' to handlers: '.implode(', ',array_keys($this->config['activeHandlers'])));
        foreach ($this->config['activeHandlers'] as $handler=>$options) {
        $core->attachModuleToHandler($handler,$this->name,$this);
        }
        return $this;
    }
	
    /**
     * BaseModule::setModuleConfig()
     * 
     * @param mixed $config
     * @return
     */
    public function setModuleConfig(array $config)
    {
        list($this->name) = array_keys($config);
        $this->config = array_shift($config);
        return $this;
    }

    /**
     * BaseModule::outro()
     * 
     * @param mixed $core
     * @return
     */
    public function outro(CoreInterface $core)
    {
        return $this;
    }
    }