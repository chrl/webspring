<?php
/**
 * Менеджер очередей RabbitMQ
 */
class RabbitManagerModule extends BaseModule implements ModuleInterface{
    
    private $queues;
    private $cnn;
    private $channel;
    private $exchanges;

    public function intro(CoreInterface $core)
    {
        $this->core = $core;

        $config = $core->getConfig()->get('settings.rabbit');



        $this->cnn = new AMQPConnection();
        $this->cnn->setHost($config['host']);
        $this->cnn->setPort($config['port']);

        if(isset($config['login'])) {
            $this->cnn->setLogin($config['login']);
            $this->cnn->setPassword($config['pwd']);
        }

        $this->connect();

    }

    function __destruct() {
        $this->cnn->disconnect();
    }
    /**
     * Создает подключение
     */
    private function connect(){
        $this->cnn->connect();

        if($this->cnn->isConnected())
        {
            $this->channel = new AMQPChannel($this->cnn);
            $this->channel->setPrefetchCount(1);
        }
    }
    
    /**
     * Создает эксчендж
     * @param string $name имя эксченджа
     * @param string $type тип экченджа
     * @param boolean $declare 
     */
    public function createExchange($name, $type = AMQP_EX_TYPE_DIRECT, $declare=true){
        if(!isset($this->exchanges[$name]) && $this->channel->isConnected()){
            $this->exchanges[$name] = new AMQPExchange($this->channel);
            $this->exchanges[$name]->setName($name);
            $this->exchanges[$name]->setType($type);
            $this->exchanges[$name]->setFlags(AMQP_DURABLE);
            if($declare){
                $this->exchanges[$name]->declareExchange();
            }        
        }
    }

    /**
     * Создает очередь
     * @param string $ex_name имя эксченджа
     * @param string $name имя очереди
     * @param string $routing_key ключ маршрутизации
     * @param boolean $declare
     * @return int количество сообщений в очереди
     */
    public function createQueue($ex_name, $name, $routing_key, $declare=true){
        $cnt = 0;
        if($this->channel->isConnected()){
            if(!isset($this->exchanges[$ex_name])){
                $this->createExchange($ex_name);
            }
            
            if(!isset($this->queues[$name])){
                $this->queues[$name] = new RabbitQueue($this->channel, $this->exchanges[$ex_name], $name);
                $this->queues[$name]->linkCore($this->core);

                if($declare){
                    $cnt = $this->queues[$name]->declareQueue();
                }
                $this->queues[$name]->bind($ex_name, $routing_key);
            } else{
                $cnt = 1;
            }
        }
    }
    
    /**
     * Возвращает канал
     * @return AMQPChannel
     */
    public function getChannel(){
        return $this->channel;
    }
    
    /**
     * Возвращает эксчендж по имени
     * @param string $name имя эксченджа
     * @return AMQPExchange
     */
    public function getExchange($name){
        return $this->exchanges[$name];
    }

    /**
     * Возвращает очередь по имени
     * @param type $name имя очереди
     * @return AMQPQueue
     */
    public function getQueue($name){

        if (!isset($this->queues[$name])) {
            $this->createQueue('ex_'.$name, $name, 'routing.'.$name);
        }

        return $this->queues[$name];
    }
}

