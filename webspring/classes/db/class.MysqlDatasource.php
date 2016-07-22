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
            $this->connection = mysqli_connect($options['host'],$options['user'],$options['pass']);
            $database = mysqli_select_db($this->connection, $options['database']);
            
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
            $res = mysqli_query($this->connection, $sql);
            
            if (mysqli_error($this->connection)) {
                $this->getCore()->getLogger()->log('Query led to error: '.  mysqli_error($this->connection));
            }

            $result = array();

            if (is_bool($res)) return $res;


            while ($row = mysqli_fetch_assoc($res)) {
                $result[$row['id']] = $row;
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
            mysqli_close($this->connection);
            $this->getCore()->getLogger()->log('Shut down connection to '.$this->name.' datasource');
        }
        
        public function insert(EntityInterface &$entity)
        {
            $sql = "insert into `".$entity->getTable()."` set ";
            
            $props = $entity->getProperties();
            
            $properties = array();
            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_real_escape_string($this->connection,$item).'"');
            }
            
            $sql.= implode(',', $properties);
            $this->query($sql);
            $entity->setId(mysqli_insert_id($this->connection));
            
            return $this;
        }
        
        public function update(EntityInterface &$entity)
        {
            $sql = "update `".$entity->getTable()."` set ";
            $props = $entity->getProperties();
            $properties = array();

            foreach ($props as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_real_escape_string($this->connection,$item).'"');
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
                if ($item[0]=='%') {
                    $properties[] = $key.' LIKE '.'"'.mysqli_real_escape_string($this->connection,$item).'"';
                } else

                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_real_escape_string($this->connection,$item).'"');
            }
            
            $sql.= implode(' AND ', $properties);
            $result = $this->query($sql);
            
            if ($result && (count($result)==1)) return array_shift($result);
            
            return $result;   

        }

        public function getLast(EntityInterface &$entity,$params)
        {
            $sql = "select * from `".$entity->getTable()."` where ";

            $properties = array();

            foreach ($params as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_real_escape_string($this->connection,$item).'"');
            }

            $sql.= implode(' AND ', $properties);
            $sql.=' order by id desc limit 1';
            $result = $this->query($sql);

            if ($result && (count($result)==1)) return array_shift($result);

            return $result;

        }

        public function getFirst(EntityInterface &$entity,$params)
        {
            $sql = "select * from `".$entity->getTable()."` where ";

            $properties = array();

            foreach ($params as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_real_escape_string($this->connection,$item).'"');
            }

            $sql.= implode(' AND ', $properties);
            $sql.=' limit 1';
            $result = $this->query($sql);

            if ($result && (count($result)==1)) return array_shift($result);

            return $result;

        }

        public function updateByParams(EntityInterface &$entity,$params,$updateArray)
        {

            $properties = array();
            $set = "SET ";
            foreach ($updateArray as $key=>$item)
            {
                $properties[] = $key.' = '.(is_numeric($item) ? $item : '"'.mysqli_escape_string($this->connection,$item).'"');
            }

            $set.= implode(', ', $properties);

            $sql = "UPDATE `".$entity->getTable()."` ".$set." WHERE ";

            $properties = array();
            if (count($params)>0) {
                foreach ($params as $key=>$item)
                {
                    $properties[] = $key.' = '.(is_int($item) ? $item : '\''.mysqli_escape_string($this->connection,$item).'\'');
                }

                $sql.= implode(' AND ', $properties);
            } else $sql.= '1=1';

            $this->query($sql);

            return true;

        }
        
    }