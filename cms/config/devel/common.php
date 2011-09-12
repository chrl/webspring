<?php

return array(

    'links'=>array(
        'database.php',
        'include.php'
    ),

    'modules'=>array(
    	'Cache'=>array(
    	    'cacheDir'=>'../cache/',
            'handler'=>'FileCacheHandler',
    	    'activeHandlers'=>array(
                    'GetPage'=>10,
                    'GetAllPages'=>10,
    	    )
    	)
    ),
    'settings'=>array(
        'logengine'=>'ScreenLogger',
        'templatedir'=>'../templates/',        
        'logdir' => '../logs/',
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


	
);
