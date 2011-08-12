<?php

return array(
    'execution' => array(
        'pages-view' => array(
            'tree' => array(
                'GetPage' => array(
                    'data'=>array(
            			'input'=>array('pageNum'),
            			'output'=>array('page'),
                    )
                )
            ),
            'condition' => array(
                'uri' => '/^\/pages\/(\d+)(:?\/)*$/',
                'match'=> array(
                    1=>'pageNum',
                )
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
        	    )
    	),
    ),
    'modules'=>array(
    	'Cache'=>array(
    	    'cacheDir'=>'/usr/local/www/spring/cache/',
            'handler'=>'FileCacheHandler',
    	    'activeHandlers'=>array(
                    'GetPage'=>100,
    	    )
    	)
    ),
    'settings'=>array(
        'logengine'=>'ScreenLogger',
        'logdir' => '/usr/local/www/spring/',
        'logs'=> array(
   	        'default'=>'default.log',
            'MysqlDatasource'=>'sql.log',
    	),
    	'debug'=>false,
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
