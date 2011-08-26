<?php

    /**
     * Logger
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    abstract class Logger implements LoggerInterface
    {
	protected $log = true;
	protected $core = null;
        
	/**
	 * Logger::setLevel()
	 * 
	 * @param mixed $debug
	 * @return
	 */
	public function setLevel($debug)
	{
	    $this->log = $debug;
	}
        
        /**
         * Logger::__construct()
         * 
         * @param mixed $core
         * @return
         */
        public function __construct(CoreInterface $core) {
            $this->core = $core;
            if ($core->getDebugLevel() === false) {
                $this->setLevel(false);
            }
            return $this;
        }

    }
?>
