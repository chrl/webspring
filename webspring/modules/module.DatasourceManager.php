<?php
    /**
     * DatasourceManagerModule
     *
     * @access public
     */
    class DatasourceManagerModule extends BaseModule implements ModuleInterface
    {
        protected $datasources = array();
        protected $mapping = array();
        
        /**
         * DatasourceManagerModule::intro()
         * 
         * @param mixed $core
         * @return $this
         */
        public function intro(CoreInterface $core) {
            $datasources = 
                    $core->
                        getConfig()->
                        get('datasources');
            
            $this->mapping = $core->
                            getConfig()->
                            get('mapping');
            
            foreach ($datasources as $datasourceName=>$datasourceOptions)
            {
                $datasourceType = $datasourceOptions['type'];
                $datasourceOptions['core'] = $core;
                $datasources[$datasourceName] = new $datasourceType($datasourceOptions);
                $datasources[$datasourceName]->setName($datasourceName);
            }
            
            $this->setDatasources($datasources);
            
            
            return $this;
        }
        
        public function getDatasource($datasourceName)
        {
            return isset($this->datasources[$datasourceName])
                        ? $this->datasources[$datasourceName]
                        : false;
        }

        /**
         * @param $entity
         * @return $this|bool|mixed
         * @throws Exception
         */
        public function getEntityDatasource($entity)
        {
            
            $entity = str_ireplace('entity', '', $entity);
            
            if (isset($this->mapping[$entity])) {
                return $this->getDatasource($this->mapping[$entity]);
            } else {
                throw new Exception('Datasource mapping not defined for entity '.$entity);
            }
        }
        
        /**
         * DatasourceManagerModule::setDatasources()
         * 
         * @param mixed $datasources
         * @return void
         */
        protected function setDatasources(array $datasources) {
            $this->datasources = $datasources;
        }
        
        /**
         * DatasourceManagerModule::outro()
         * 
         * @param mixed $core
         * @return void
         */
        public function outro(CoreInterface $core) {
            $core->getLogger()->log('Shutting down connections');
            foreach ($this->datasources as $datasource) {
                $datasource->shutdown();
            }
        }
    }