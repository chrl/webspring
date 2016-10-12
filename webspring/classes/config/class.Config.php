<?php

    /**
     * Config
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */

    class Config implements ConfigInterface
    {
        private $settings = array();
        
        private $extends = array();
        
        protected static $globalConfig = array();


        /**
         * Config::__toString()
         * 
         * @return
         */
        public function  __toString() {
            return 'Printing Config prohibited';
        }
        
        /**
         * Config::setGlobalConfig()
         * 
         * @param mixed $Config
         * @return
         */
        public static function setGlobalConfig(Config $Config)
        {
            self::$globalConfig = $Config;
        }
        
        /**
         * Config::getGlobalConfig()
         * 
         * @return
         */
        public static function getGlobalConfig()
        {
            return self::$globalConfig;
        }

        /**
         * Config::__construct()
         * 
         * @param bool $config
         * @return
         */
         
        public function __construct($config = false)
        {
                $this->settings = $this->readConfig($config);
                return $this;
        }
        
        public function readConfig($config=false)
        {
            if ($config == false) {
                $config = 'default.php';
            }
            
            if (in_array($config,$this->extends)) {
                return array();
            }
            
            $this->extends[]=$config;

            $startDir = getcwd().'/../config/current/';

            if (file_exists($startDir.$config))
            {
                $settings = require_once($startDir.$config);
                
                while (!empty($settings['links'])) {
                    foreach ($settings['links'] as $key=>$item) {
                        $set2 = $this->readConfig($item);
                        unset($settings['links'][$key]);
                        $settings = array_merge($settings,$set2);
                    }
                }
                
                return $settings;
                
            } else {
                throw new Exception('Config file not exist: '.$startDir.$config);
            }
            return array();
            
        }
	
        /**
         * Config::get()
         * 
         * @param mixed $key
         * @return
         */
        public function get($key)
        {
            if (false!==strpos($key,'.')) {
                list($section,$value) = explode('.',$key);
                if (isset($this->settings[$section][$value]))
                {
                    return $this->settings[$section][$value];
                }
            } else {
                if (key_exists($key, $this->settings))
                {
                    return $this->settings[$key];
                }

            }
            return false;

        }

    }



?>
