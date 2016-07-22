<?php

    /**
     * DatasourceAbstract
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright charlie@chrl.ru
     * @version 2011
     * @access public
     */
    abstract class DatasourceAbstract implements DatasourceInterface
    {
        
        protected $name = 'default';
        protected $core = null;
        
        /**
         * DatasourceAbstract::setName()
         * 
         * @param mixed $name
         * @return
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }
        /**
         * DatasourceAbstract::linkCore()
         * 
         * @param mixed $core
         * @return
         */
        public function linkCore(CoreInterface $core)
        {
            $this->core = $core;
            return $this;
        }
        
        /**
         * DatasourceAbstract::getCore()
         * 
         * @return
         */
        public function getCore()
        {
            return $this->core;
        }
        
        /**
         * DatasourceAbstract::fetch()
         * 
         * @param mixed $from
         * @param mixed $what
         * @param mixed $where
         * @param mixed $limit
         * @return
         */
        public function fetch($from, $what, $where, $limit)
        {
            if ($what != '*') {
                $what = implode(', ',$what);
            }
            
            if ($where && !empty($where)) {
                
                $whereArray = array();
                
                
                
                foreach ($where as $key=>$item) {
                    if (is_numeric($item))
                    {
                        $whereArray[]='`'.$key.'`='.$item;
                    } elseif(is_array($item)) {
                        
                        $whereArray[]='`'.$key.'` IN ('.implode(', ',$item).')';
                        
                    } else {
                        $whereArray[]='`'.$key.'`='.mysql_real_escape_string($item);
                    }
                }
                
                $whereStr = implode(' AND ',$whereArray);
                
            } else {
                $whereStr = '1';
            }
            
            $sql = "SELECT ".$what." FROM ".$from." WHERE ".$whereStr.($limit>0?(" LIMIT ".$limit):'' );
            
            
            return $this->query($sql);
        }
    }