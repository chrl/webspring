<?php
    class RedisCacheHandler extends Linkable implements CacheHandlerInterface, LinkableInterface
    {
        public $expireTime = 3600;
        protected $transport = null;
   
        public function __construct() {
             $this->transport = new Redis();
             $this->transport->connect('127.0.0.1', 6379);
        }
        
        public function setExpireTime($expireTime)
        {
            $this->expireTime = $expireTime;
            return $this;
        }
        
        public function get($key)
        {
    	    $this->core->getLogger()->log('Checking redis record '.$key.'.cache');
            $this->core->getLogger()->log('Got expire time: '.$this->expireTime);
            
            $result = $this->transport->get($key.'.cache');
            
            
            
            if ($result) {
                $res = unserialize($result);
                if (isset($res['time']) && isset($res['data'])) {
                   
                    if (time()- $res['time'] > $this->expireTime) {
                         $this->core->getLogger()->log('Cache expired, cleaning');
                         $this->transport->del($key.'.cache');
                    } else {
                        return $res['data'];
                    }
                    
                } else {
                    $this->core->getLogger()->log('Cache record invalid: '.$result); 
                    return false;
                }
            }
            
            return false;
        }
        
        public function set($key,$value)
        {
            
            
            
            $this->transport->set($key.'.cache',  serialize(array('data'=>$value,'time'=>time())));
            
            return $this;            
        }
    }