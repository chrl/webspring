<?php

    class SubEntity extends BaseEntity implements EntityInterface
    {
        protected $table = 'subs';

        public function markActualSubs() {
            $datasource = $this->
                core->
                getModule('DatasourceManager')->
                getEntityDatasource(get_class($this));

            $table = $this->getTable();

            $datasource->query("update ".$table." set processing_status = 1, state_change_date = unix_timestamp() where active = 1 and processing_status = 0 and bill_time < unix_timestamp();");

        }
    }