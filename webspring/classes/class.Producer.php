<?php

    class Producer extends Linkable implements LinkableInterface
    {

        public function getCore()
        {
            return $this->core;
        }
        
        public function produce($entity,$params = array())
        {
            $entity.='Entity';
            $entity = new $entity($this->core,$params);
            return $entity;
        }
        
        public function produceCollection($entity)
        {
            $entity.='Entity';
            return new Collection($this,$entity,array());
        }
    }