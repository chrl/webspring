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
        protected $sid = '';

        protected $outputLevel = 3;

        const CRITICAL = 1;
        const WARNING = 2;
        const NOTICE = 3;


        public function setOutputLevel($level) {
            $this->outputLevel = $level;
            return $this;
        }

        public function notice($message)
        {
            $this->log($message,'default',Logger::NOTICE);
            return $this;
        }

        public function warning($message)
        {
            $this->log($message,'default',Logger::WARNING);
            return $this;
        }

        public function critical($message)
        {
            $this->log($message,'default',Logger::CRITICAL);
            return $this;
        }
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

        public function setSid($sid) {
            $this->sid = $sid;
            return $this;
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
