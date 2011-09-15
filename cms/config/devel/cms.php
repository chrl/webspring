<?php

return array(

    'links'=>array(
        'common.php',
    ),

    'execution' => array(
        'admin'=>array(
            'tree'=>array(
                'GetSession'=>array(
                    'ok'=>array(
                        'SetRequest'=>array(
                            'data' => array(
                                'template'=>'admin.tpl',
                            ),
                        ),
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
        'logout'=>array(
            'tree'=>array(
                'GetSession'=>array(
                    'ok'=>array(
                        'ClearSession'=>array(
                            'ok'=>array(
                                'Redirect'=>array(
                                    'data'=> array(
                                        'path'=>'/admin/',
                                    )
                                )
                            )
                        ),
                    ),
                    'fail'=>array(
                        'Redirect'=>array(
                            'data'=> array(
                                'path'=>'/admin/',
                            )
                        )
                    ),
                ),
            ),
            'condition'=>array(
                'uri'=>'/^\/logout\/$/',
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

	
);
