<?php


    /**
     * FileLogger
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class FileLogger extends Logger implements LoggerInterface
    {
    
        /**
         * FileLogger::log()
         * 
         * @param mixed $message
         * @param string $calledClass
         * @return
         */
        public function log($message,$calledClass = 'default')
        {
            if (!$this->log) return $this;
            
            $logs = $this->core->getConfig()->get('settings.logs');
            $logDir = $this->core->getConfig()->get('settings.logdir');
            
            $bt = debug_backtrace();
            preg_match('/([^\.^\/]+)\.php$/',$bt[0]['file'],$matches);
            $calledClass = $matches[1];
    
            if ($calledClass == 'FileLogger') $calledClass = $matches[2];
            
            $log = isset($logs[$calledClass])
                        ? $logs[$calledClass]
                        : $logs['default'];
            
            $message = '['.$this->sid.']['.date('d.m.Y H:i:s').'] '.$calledClass.': '.$message."\r\n";

            $f = fopen($logDir.$log,"a");
            fputs($f,$message);
            fclose($f);
        }
    }