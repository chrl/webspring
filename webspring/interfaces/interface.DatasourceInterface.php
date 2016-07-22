<?php

    interface DatasourceInterface
    {
        /**
         * fetch()
         * 
         * @param mixed $from
         * @param mixed $what
         * @param mixed $where
         * @param mixed $limit
         * @return
         */
        public function fetch($from,$what,$where,$limit);
    }