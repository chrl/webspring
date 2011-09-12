<?php

    /**
     * GetPage
     * 
     * @package bullsh
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetPageProcessor extends BaseProcessor implements ProcessorInterface
    {
        
    	public function run($data, CoreInterface $core)
    	{
            $producer = new Producer($core);
            
            $page = $producer->
                        produce('Page')->
                        getById(
                            $core->
                                getRequest()->
                                get('pageNum')
                        );
                        
            return array($page?'ok':'not-exist',array('page'=>$page));
    	}
    }