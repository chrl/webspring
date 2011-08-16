<?php

return array(
    'execution' => array(
        'pages-view' => array(
            'tree' => array(
                'GetPage' => array(
                    'data'=>array(
            			'input'=>array('pageNum'),
            			'output'=>array('page'),
                    ),
                    'not-exist'=>'default-path'
                )
            ),
            'condition' => array(
                'uri' => '/^\/pages\/(\d+)(:?\/)*$/',
                'match'=> array(
                    1=>'pageNum',
                )
            ),
            'set' => array(
                'template'=>'test.tpl',
            )
        ),        

    	'default-path' => array(
    	    'tree' => array (
            		'SetRequest' => array(
                		    'data'=> array(
                			     'message'=>'Undefined request found',
          		            ),
            		)
        	    ),
        	    'condition' => array(
            		'request'=> array (
            		    
            		)
        	    ),
                'set'=> array(
                    'template'=>'error.tpl',
                )
    	),
    ),
    'modules'=>array(
    	'Cache'=>array(
    	    'cacheDir'=>'/usr/local/www/spring/cache/',
            'handler'=>'FileCacheHandler',
    	    'activeHandlers'=>array(
                    'GetPage'=>10,
    	    )
    	)
    ),
    'settings'=>array(
        'logengine'=>'FileLogger',
        'logdir' => '/usr/local/www/spring/logs/',
        'templatedir'=>'/usr/local/www/spring/templates/',
        'logs'=> array(
   	        'default'=>'default.log',
            'MysqlDatasource'=>'sql.log',
            'Counter'=>'slow.log',
            'PgsqlDatasource'=>'sql.log',
    	),
    	'debug'=>true,
        'headers'=>array(
            'Content-Type'=> 'text/html; charset=UTF-8',
        )
    ),
    'datasources'=>array(
        'devel'=> array(
            'type'=>'PgsqlDatasource',
            'host' => 'database1.stand',
            'database' => 'test',
            'user' => 'postgres',
            'pass' => 'postgres',
        ),
    ),
    'mapping'=>array(
        'User'=>'devel',
        'Page'=>'devel',
    )

	
);
