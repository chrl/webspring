<?php

/**
 * RabbitMQ
 */
class RabbitQueue extends Linkable implements QueueInterface, LinkableInterface {
    
    private $name;
    private $cur_item;
    private $exchange;
    private $client;

    public function __construct(AMQPChannel $channel, AMQPExchange $exchange, $name){
        $this->name = $name;
        $this->exchange = $exchange;
        $this->client = new AMQPQueue($channel);
        $this->client->setName($name);
        $this->client->setFlags(AMQP_DURABLE);
    }

    public function push(array $data){

        $this->core->getLogger()->log('pushing data to '.$this->getFullName().': '.var_export($data,true));
        return $this->exchange->publish(json_encode($data),'routing.'.$this->name);
    }
    
    public function get(){

        $item = $this->client->get(); #AMQP_AUTOACK

        if($item){
            $this->cur_item = $item;
            return json_decode($item->getBody(), true);
        }
    }
    
    public function getCurrent(){
        return $this->cur_item;
    }

    public function ack(){
        if($this->cur_item){
            return $this->client->ack($this->cur_item->getDeliveryTag());
        }
    }
    
    public function __call($name, $arguments){
        if (method_exists($this->client, $name)){
            call_user_func_array(array($this->client, $name), $arguments);
        } else{
            throw new Exception("Unknown method $name");
        }
    }
    /**
     * Возвращает полное название очереди в RabbitMQ
     * @return string
     */
    public function getFullName(){
        return 'q_'.$this->name;
    }
}