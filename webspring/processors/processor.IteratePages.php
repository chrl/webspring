<?php

    /**
     * IteratePages
     * 
     * @package bullsh
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class IteratePagesProcessor extends BaseProcessor implements ProcessorInterface
    {
        
    	public function run($data, CoreInterface $core)
    	{
            $page = array('name'=>'Pages List','text'=>'<ul>');
            
            foreach ($core->getRequest()->get('pages') as $key=>$value)
            {
                $page['text'].='<li><a href="/pages/'.$value['id'].'/">'.$value['name'].'</a></li>';
            }
            
            $page['text'].='</ul>';
            
            return array('ok',array('page'=>$page));
    	}
    }