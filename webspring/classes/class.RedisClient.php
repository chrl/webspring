<?php

class RedisClient extends Linkable implements LinkableInterface
{

    protected $transport = null;

    public function __construct() {
        $this->transport = new Redis();
        $this->transport->connect('127.0.0.1', 6379);
    }

    public function setex($key,$timeout,$data)
    {
        return $this->transport->setex($key,$timeout,json_encode($data));
    }

    public function rpush($key,$value) {
        return $this->transport->rPush($key, $value);
    }

    public function lpop($key) {
        return $this->transport->lPop($key);
    }

    public function get($key)
    {
        $data = $this->transport->get($key);
        return $data
               ? json_decode($data,true)
               : false;

    }

    public function set($key,$data)
    {
        return $this->transport->set($key,json_encode($data));
    }


    public function __destruct()
    {
        $this->transport->close();
    }


}