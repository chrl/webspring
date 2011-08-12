<?php

    /**
     * MysqlDatasource
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    class MysqlDatasource extends DatasourceAbstract implements DatasourceInterface
    {
        
        /**
         * MysqlDatasource::__construct()
         * 
         * @param mixed $options
         * @return
         */
        public function __construct($options)
        {
            $this->linkCore($options['core']);
            $this->getCore()->getLogger()->log('Connecting to datasource '.$options['user'].'@'.$options['host']);
            $this->connection = mysql_connect($options['host'],$options['user'],$options['pass']);
            $database = mysql_select_db($options['database'], $this->connection);
            
            if (!isset($options['encoding'])) $options['encoding'] = 'utf8';
            $this->query("SET NAMES ".$options['encoding']);
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
            $res = mysql_query($sql,$this->connection);
            
            if (mysql_error($this->connection)) {
                $this->getCore()->getLogger()->log('Query led to error: '.  mysql_error($this->connection));
            }
            
            $result = array();

            if($res && !is_bool ($res) && (mysql_num_rows($res)>0))
            {
                while (false !== $row = mysql_fetch_assoc($res)) {
                    $result[$row['id']] = $row;
                }
                    
            } else return false;
            
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
            mysql_close($this->connection);
            $this->getCore()->getLogger()->log('Shut down connection to '.$this->name.' datasource');
        }
        
        public function insert(EntityInterface &$entity)
        {
            $sql = "insert into `".$entity->getTable()."` set ";
            
            $props = $entity->getProperties();
            
            $properties = array();
            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysql_real_escape_string($item,$this->connection)).'"';
            }
            
            $sql.= implode(',', $properties);
            $this->query($sql);
            $entity->setId(mysql_insert_id($this->connection));
            
            return $this;
        }
        
        public function update(EntityInterface &$entity)
        {
            $sql = "update `".$entity->getTable()."` set ";
            $props = $entity->getProperties();
            $properties = array();

            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysql_real_escape_string($item,$this->connection)).'"';
            }
            
            $sql.= implode(',', $properties)." where id = ".$entity->getId();
            $this->query($sql);

            return $this;
        }
        
        public function getByParams(EntityInterface &$entity,$params)
        {
            $sql = "select * from `".$entity->getTable()."` where ";

            $properties = array();

            foreach ($params as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysql_real_escape_string($item,$this->connection).'"');
            }
            
            $sql.= implode(' AND ', $properties);
            $result = $this->query($sql);
            
            if (count($result)==1) return array_shift($result);
            
            return $result;   

        }
        
    }