<?php
    class FileCacheHandler extends Linkable implements CacheHandlerInterface, LinkableInterface
    {
        public $expireTime = 3600;
        
        public function setExpireTime($expireTime)
        {
            $this->expireTime = $expireTime;
            return $this;
        }
        
        public function get($key)
        {
    	    $this->core->getLogger()->log('Checking file csh.'.$key.'.cache');
            $this->core->getLogger()->log('Got expire time: '.$this->expireTime);
            
            if (file_exists($this->core->getModule('Cache')->config['cacheDir'].'csh.'.$key.'.cache')) {
                
                if (time() - filemtime($this->core->getModule('Cache')->config['cacheDir'].'csh.'.$key.'.cache') > $this->expireTime) {
                    $this->core->getLogger()->log('File expired, cleaning cache');
                    unlink($this->core->getModule('Cache')->config['cacheDir'].'csh.'.$key.'.cache');
                    return false;
                }
                
        		$cached = file_get_contents($this->core->getModule('Cache')->config['cacheDir'].'csh.'.$key.'.cache');
        		$cached = unserialize($cached);
        		return $cached; 
    	    }
            return false;
        }
        
        public function set($key,$value)
        {
            file_put_contents($this->core->getModule('Cache')->config['cacheDir'].'csh.'.$key.'.cache',  serialize($value));
            return $this;            
        }
    }