<?php
    interface CacheHandlerInterface
    {
        public function get($key);
        public function set($key,$value);
    }