<?php

    /**
     * PgsqlDatasource
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    class PgsqlDatasource extends DatasourceAbstract implements DatasourceInterface
    {
        
        /**
         * PgsqlDatasource::__construct()
         * 
         * @param mixed $options
         * @return
         */
        public function __construct($options)
        {
            $this->linkCore($options['core']);
            $this->getCore()->getLogger()->log('Connecting to datasource '.$options['user'].'@'.$options['host']);
            $this->connection = pg_connect('host='.$options['host'].' port=5432 dbname='.$options['database'].' user='.$options['user'].' password='.$options['pass']);
            
            if (!isset($options['encoding'])) {
                $options['encoding'] = 'utf8';
            }
            
            pg_set_client_encoding($this->connection,"UNICODE");
        }
        
        /**
         * MysqlDatasource::query()
         * 
         * @param mixed $sql
         * @return
         */
        public function query($sql)
        {
            $this->getCore()->getLogger()->log('Running query: '.$sql);
            $res =@pg_query($this->connection,$sql);
            
            if (pg_last_error($this->connection)) {
                $this->getCore()->getLogger()->log('!!! Query led to error: '.  pg_last_error($this->connection));
            }
            
            $result = array();

            if($res && !is_bool ($res) && (pg_num_rows($res)>0))
            {
                while (false !== $row = pg_fetch_assoc($res)) {
                    $result[$row['id']] = $row;
                }
                    
            } else {
                return false;
            }
            
            return $result;
        }
        
        /**
         * MysqlDatasource::shutdown()
         * 
         * @return
         */
        public function shutdown() 
        {
            $this->query('ROLLBACK;');
            pg_close($this->connection);
            $this->getCore()->getLogger()->log('Shut down connection to '.$this->name.' datasource');
        }
        
        public function insert(EntityInterface &$entity)
        {
            $sql = "insert into `".$entity->getTable()."` set ";
            
            $props = $entity->getProperties();
            
            $properties = array();
            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.pg_escape_string($this->connection,$item)).'"';
            }
            
            $sql.= implode(',', $properties);
            $this->query($sql);
            $entity->setId(false);
            
            return $this;
        }
        
        public function update(EntityInterface &$entity)
        {
            $sql = "update `".$entity->getTable()."` set ";
            $props = $entity->getProperties();
            $properties = array();

            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.pg_escape_string($this->connection,$item)).'"';
            }
            
            $sql.= implode(',', $properties)." where id = ".$entity->getId();
            $this->query($sql);

            return $this;
        }
        
        public function getByParams(EntityInterface &$entity,$params)
        {
            $sql = "select * from \"".$entity->getTable()."\" where ";

            $properties = array();
            if (count($params)>0) {
                foreach ($params as $key=>$item)
                {
                    $properties[] = $key.' = '.(is_int($item) ? $item : '\''.pg_escape_string($this->connection,$item).'\'');
                }
                
                $sql.= implode(' AND ', $properties);
            } else {
                $sql.= '1=1';
            }
            $result = $this->query($sql);
            
            if ($result && (count($result)==1)) {
                return array_shift($result);
            }
            
            return $result;   

        }
        
    }