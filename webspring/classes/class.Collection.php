<?php

    class Collection implements Iterator,  Countable
    {
        protected $objects = array();
        protected $position = 0;
        protected $type = 'BaseEntity';
        protected $core = null;
        
        public function __construct($datasource, $entityName,$rows)
        {
            
            $this->objects = array();
            $this->position = 0;
            $this->type = $entityName;
            $this->core = $datasource->getCore();
            
            foreach ($rows as $map) {
                $this->objects[] = new $entityName($datasource->getCore(),$map);
            }
            
            return $this;
        }
         
        public function __toString()
        {
            return nl2br(var_export($this->objects,true));
        }
        
        public function current()
        {
            return $this->objects[$this->position];
        }
        
        public function next()
        {
            ++$this->position;
            return $this;
        }
        
        public function rewind()
        {
            $this->position = 0;
            return $this;
        }
        
        public function key()
        {
            return $this->position;
        }
        
        public function valid()
        {
            return isset($this->objects[$this->position]);
        }
        
        public function count()
        {
            return count($this->objects);
        }
        
        
        // straight access
        public function getByValue($key,$value)
        {
            foreach ($this->objects as $item)
            {
                if ($item->$key == $value) {
                    return $item;
                }
            }
            
            return false;
        }
        
        public function combine($entity,$combination,$properties=false)
        {
            $ids = array();
            
            
            
            foreach ($this->objects as $key=>$item)
            {
                if (!in_array($item->$combination[0],$ids)){
                   $ids[]=$item->$combination[0];
                }
            }

            $collection = call_user_func($entity.'::getAll',array($combination[1]=>$ids),$properties);
            
            foreach ($this->objects as $key=>$item)
            {
                $this->objects[$key]->$entity = $collection->getByValue('id',$item->$combination[0]);
            }
            
            return $this;
            
        }
        
        public function fillByParams($params)
        {
            $res = $this->
                        core->
                        getModule('DatasourceManager')->
                        getEntityDatasource($this->type)->
                        getByParams(new $this->type($this->core),$params);
            
            if ($res) {
                foreach ($res as $map) {
                $this->objects[] = new $this->type($this->core,$map);
            }
            }
            
            return $this;
        }
        
        public function toArray()
        {
            $array = array();
            foreach ($this->objects as $key=>$item)
            {
                if(is_a($item, 'Collection')) {
                    $array[$key]=$item->toArray();
                } else {
                    $array[$key]=$item->getProperties();
                }
            }
            return $array;
        }


        public function getWithUpdate($params,$updateArray)
        {
            $res = $this->
                core->
                getModule('DatasourceManager')->
                getEntityDatasource($this->type)->
                getByParams(new $this->type($this->core),$params);

            if (isset($res['id'])) {
                $res = array($res);
            }


            $this->
                core->
                getModule('DatasourceManager')->
                getEntityDatasource($this->type)->
                updateByParams(new $this->type($this->core),$params,$updateArray);


            if ($res) {
                foreach ($res as $map) {
                $this->objects[] = new $this->type($this->core,$map);
            }
            }

            return $this;
        }

        public function updateByIds($objects,$updateArray)
        {
            $ids = array();

            foreach ($objects as $item) {
                $ids[] = $item['id'];
            }

            $this->
            core->
            getModule('DatasourceManager')->
            getEntityDatasource($this->type)->
            updateByIds(new $this->type($this->core),$ids,$updateArray);

            return $this;
        }


    }