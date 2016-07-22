<?php
    abstract class Linkable implements LinkableInterface
    {
        public $core = null;
        
        public function linkCore(CoreInterface $core)
        {
            $this->core = $core;
            return $this;
        }        
    } 