<?php

    interface LoggerInterface
    {
        /**
         * log()
         * 
         * @param mixed $message
         * @param string $calledClass
         * @return
         */
        public function log($message,$calledClass = 'default');
    }