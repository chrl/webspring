<?php

    /**
     * ScreenLogger
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class ScreenLogger extends Logger implements LoggerInterface
    {

        /**
         * ScreenLogger::log()
         * 
         * @param mixed $message
         * @param string $calledClass
         * @return
         */
        public function log($message,$calledClass = 'default')
        {
            
            if (!$this->log) return $this;
	    
            $bt = debug_backtrace();
            preg_match('/([^\.^\/]+)\.php$/',$bt[0]['file'],$matches);
            $calledClass = $matches[1];

            if ($calledClass == 'ScreenLogger') $calledClass = $matches[2];

            $message = '<b>['.date('d.m.Y H:i:s.u',microtime(true)).']</b> <span style="min-width:200px;display:inline-block;text-align:right;"><u>'.$calledClass.'</u> &rarr;</span> '.$message;
            
            echo $message.'<br />';
        }
    }