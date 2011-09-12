<?php

    return array(
        'links'=>array('common.php'),
        
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
    
    );