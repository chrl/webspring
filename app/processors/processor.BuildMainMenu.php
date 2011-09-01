<?php

    /**
     * BuildMainMenu
     * 
     * @package bullsh
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class BuildMainMenuProcessor extends BaseProcessor implements ProcessorInterface
    {
        
    	public function run($data, CoreInterface $core)
    	{
            $page = '';
            $links = array();
            $items = $core->getRequest()->get('menu_items');
            
            $orig = $core->getRequest()->get('path');
            foreach ($items as $path=>$item)
            {
                if (isset($item['uri']))
                {
                    if ($path!=$orig) {
                        $links[]= '<a href="'.$item['uri'].'">'.$item['name'].'</a>'; 
                    } else $links[]= '<b>'.$item['name'].'</b>';
                }
            }
            
            $page = implode(' | ',$links);
            
            return array('ok',array('main_menu_text'=>$page));
    	}
    }