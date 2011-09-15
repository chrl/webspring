<?php

return array(

    'links'=>array(
        'common.php',
    ),

    'execution' => array(
        'catch'=>array(
            'tree'=> array(
                'GetCatchData'=>array(
                    'ok' => array(
                        'CatchData'=>array(
                            'ok'=> array(
                                
                            ),
                            'fail'=> array(
                            
                            )
                        )
                    ),
                ),
            ),
            'condition'=>array(
                'request'=>array(
                    'method' => 'catch',
                    'entity' => '/.+form$/',
                    'data'=>false,
                ),
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
