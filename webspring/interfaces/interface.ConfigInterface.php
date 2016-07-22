<?php

    interface ConfigInterface
    {
        /**
         * __construct()
         * 
         * @param bool $config
         * @return
         */
        public function __construct($config = false);
        /**
         * get()
         * 
         * @param mixed $key
         * @return
         */
        public function get($key);
    }