<?php
    class MemoryCacheHandler extends Linkable implements CacheHandlerInterface, LinkableInterface
    {
        protected $transport = null;
        public function __construct()
        {
            $this->transport =  new Memcached();
            //$this->transport->addServer('127.0.0.1', 11211);
            var_dump($this->transport->getResultMessage());
        }
        
        public function get($key)
        {
    	    $this->core->getLogger()->log('Checking record '.$key);
            
            $this->transport->set($key,'BlaBla',3600);
//            $value = $this->transport->get($key);
            
            
            return $value;
        }
        
        public function set($key,$value)
        {
            $this->core->getLogger()->log('Setting record '.$key);
            $this->transport->set($key,$value,3600);
            return $this;            
        }
    }