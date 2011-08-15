<?php

return array(
    'execution' => array(
	'stop-subscribe' => array(
	    'tree'=> array(
		'SetRequest'=> array(
		    'data'=> array(
			'message'=>'Подписка остановлена!',
		    )
		)
	    ),
	    'condition'=>array(
		'request'=> array(
		    'p'=>'/\d+/',
		    'msg'=>'/(STOP|СТОП)/',
		)
	    )
	),
        'horo-get' => array (
            'tree' => array(
                'GetDate'=> array(
                    'ok'=> array(
                        'ParseSign'=> array(
                            'ok' => array(
                                'GetHoroscope' => array(
                                    'data'=> array(
                                        'input' => array(
                                            'signId',
                                            'date'
                                        ),
                                    ),
                                    'ok'=> array(
                                        'SetRequest'=> array(
                                            'data' => array(
                                                'message' => 'Гороскоп %sign% на %date%: %horoscope%',
                                            )
                                        )
                                    )

                                )
                            ),
                            'fail' => array(
                                'SetRequest'=> array(
                                    'data' => array(
                                        'message' => 'Не найден указанный знак',
                                    )
                                )

                            ),
                        ),
                    )
                )
                
            ),
            'condition' => array(
                'request' => array(
                    'id'=>'horo',
                    'sign'=>false,
                )
            )
        ),
        
        'afor-get' => array (
            'tree' => array(
                'GetDate'=> array(
                    'ok'=> array(
                        'GetAphorism' => array(
                            'data'=> array(
                                'input' => array(
                                    'date'
                                ),
                            ),
                            'ok'=> array(
                                'SetRequest'=> array(
                                    'data' => array(
                                        'message' => 'Афоризм дня на %date%: %aphorism% // %author%',
                                    )
                                )
                            )
                        ),
                    )
                )
                
            ),
            'condition' => array(
                'request' => array(
                    'id'=>'afor',
                    
                )
            )
        ),
        
	'test-path' => array(
	    'tree' => array(
		'CheckInputParams' => array(
		    'data'=>array(
			'input'=>array(
			    'test2'
			),
			'output'=>array(),
		    ),
		    'success' => array(
			'SetRequest' => array(
			    'data'=>array(
				'message'=>'CheckInput Succeeded',
			    ),

			),
		    ),
		),
	    ),
	    'condition' => array(
		'request' => array(
			'test'=>'test',
			'test2'=>false,
		),
	    )
	),
        
        'charger-path' => array(
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
    	    'activeHandlers'=>array(
    		'CheckInputParams'=>3600,
                    'DoUselessThings'=>1000,
                    'GetHoroscope'=>1000,
                    'GetAphorism'=>1000,
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
        'Distribution'=>'devel',
        'Channel'=>'devel',
        'User'=>'devel',
        'Page'=>'devel',
    )

	
);
