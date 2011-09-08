<?php

return array(
    'include'=>array(
        'main-menu' => array(
            'tree' => array(
                'BuildMainMenu'=> array(
                
                ),
            ),
            'set' => array(
                'menu_items'=>array(
                    'pages-view'=>array(
                        'name'=>'Page %page.name%',
                    ),
                    'main-page'=>array(
                        'name'=>'Main Page',
                        'uri'=>'/',
                    ),
                    'pages-list'=>array(
                        'name'=>'All pages',
                        'uri'=>'/pages/',
                    )
                )
            )
        ),
    ),
    'execution' => array(
        'admin'=>array(
            'tree'=>array(
                'GetSession'=>array(
                    'ok'=>array(
                    ),
                    'fail'=>array(
                        'SetRequest'=>array(
                            'data' => array(
                                'template'=>'login.tpl',
                            ),
                        ),
                    
                    ),
                ),
            ),
            'condition'=>array(
                'uri'=>'/^\/admin\/(.*)$/',
                'match'=>array(
                    1=>'section',
                ),
            
            ),
        ),
        'pages-view' => array(
            'tree' => array(
                'GetPage' => array(
                    'data'=>array(
            			'input'=>array('pageNum'),
            			'output'=>array('page'),
                    ),
                    'not-exist'=>'default-path',
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
        'main-page'=>array(
            'tree'=> array(
        		'SetRequest' => array(
        		    'data'=> array(
        			     'message'=>'Switching to pages-view',
  		            ),
                    'ok'=>'pages-view',
        		)            
            ),
            'condition'=> array(
                'uri'=>'/^\/$/',
            ),
            'set'=>array(
                'pageNum'=>2,
            ),
        ),
        'pages-list'=>array(
            'tree'=> array(
                'GetAllPages'=>array(
                    'output'=>array(
                        'pages',
                    ),
                    'input'=>array(
                    
                    ),
                    'ok'=>array(
                        'IteratePages'=>array(
                            'input'=>array('pages'),
                            'output'=>array('page'),
                        ),
                    ),
                ),
            ),
            'condition'=> array(
                'uri'=>'/^\/pages\/$/',
            ),
            'set'=>array(
                'template'=>'test.tpl',
            ),        
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
    	    'cacheDir'=>'../cache/',
            'handler'=>'FileCacheHandler',
    	    'activeHandlers'=>array(
                    'GetPage'=>10,
                    'GetAllPages'=>10,
    	    )
    	)
    ),
    'settings'=>array(
        'logengine'=>'ConsoleLogger',
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
