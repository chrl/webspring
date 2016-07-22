<?php

interface QueueInterface {
    /**
     * Помещает сообщение в очередь
     * @param array $data ассоциативный массив с данными
     */
    public function push(array $data);
    
    /**
     * Получает сообщение из очереди
     * @return mixed возвращает скалярное значение или ассоциативный массив
     */
    public function get();
    
    /**
     * Подтверждает получение сообщения
     */
    public function ack();
}

