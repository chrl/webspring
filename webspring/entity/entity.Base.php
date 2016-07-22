<?php

    abstract class BaseEntity implements EntityInterface
    {
        protected $properties = array();
        protected $core = null;
        protected $table = null;
        
        public function __construct($param,$param2 = array())
        {
            
            $this->core = $param;
            $this->setProperties($param2);
            return $this;
        }
        
        public function getTable()
        {
            return $this->table;
        }
        
        public function __call($name, $arguments) {
            if (substr($name,0,3)=='set') {
                $prop = strtolower(substr($name,3));
                $this->properties[$prop] = $arguments[0];
            }
            if (substr($name,0,5)=='getBy') {
                $prop = strtolower(substr($name,5));
                return $this->getByParams(array($prop=>$arguments[0]));
            }
            if (substr($name,0,3)=='get') {
                $prop = strtolower(substr($name,3));
                return isset($this->properties[$prop])
                           ? $this->properties[$prop] 
                           : false;
            }
            
            return $this;
        }
        
        public function setProperties(array $params) {
            foreach ($params as $key=>$value) {
                $this->properties[$key] = $value;
            }
            return $this;
            
        }
        
        public function getProperties()
        {
            return $this->properties;
        }
        
        public function getByParams($params)
        {
            $datasource = $this->
                        core->
                        getModule('DatasourceManager')->
                        getEntityDatasource(get_class($this));
            
            $result = $datasource->getByParams($this,$params);
            
            return $result;
        }

        public function getLast($params)
        {
            $datasource = $this->
            core->
            getModule('DatasourceManager')->
            getEntityDatasource(get_class($this));

            $result = $datasource->getLast($this,$params);

            return $result;
        }

        public function getFirst($params)
        {
            $datasource = $this->
            core->
            getModule('DatasourceManager')->
            getEntityDatasource(get_class($this));

            $result = $datasource->getFirst($this,$params);

            return $result;
        }

        public function save()
        {
            $datasource = $this->
                            core->
                            getModule('DatasourceManager')->
                            getEntityDatasource(get_class($this));

            if ($this->getId()!==false) {
                $datasource->update($this);
            } else {
                $datasource->insert($this);
            }
            return $this;
        }

    }