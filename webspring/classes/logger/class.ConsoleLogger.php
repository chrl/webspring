<?php

    /**
     * ConsoleLogger
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class ConsoleLogger extends Logger implements LoggerInterface
    {

        /**
         * ConsoleLogger::log()
         * 
         * @param mixed $message
         * @param string $calledClass
         * @return
         */
        public function log($message,$calledClass = 'default')
        {
            
            if (!$this->log) {
                return $this;
            }
	    
            $bt = debug_backtrace();
            preg_match('/([^\.^\/]+)\.php$/',$bt[0]['file'],$matches);
            $calledClass = $matches[1];

            if ($calledClass == 'ConsoleLogger') {
                $calledClass = $matches[2];
            }

            $message = '<script>console.log(\''.date('d.m.Y H:i:s',microtime(true)).' '.sprintf("%.3f",microtime(true)-floor(microtime(true))).' '.$calledClass.' - '.addslashes(str_replace("\n",'',$message)).'\');</script>';
            
            echo $message;
        }
    }