<?php

    /**
     * GetAllPages
     * 
     * @package bullsh
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetAllPagesProcessor extends BaseProcessor implements ProcessorInterface
    {
        
    	public function run($data, CoreInterface $core)
    	{
            $producer = new Producer($core);
            
            $pages = $producer->
                        produceCollection('Page')->
                        fillByParams(array())->
                        toArray();
                        
            return array($pages?'ok':'not-exist',array('pages'=>$pages));
    	}
    }