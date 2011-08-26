<?php

    /**
     * GetDate
     * 
     * @package HoroSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetDateProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * GetDate::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
    
        
        
        
	public function run($data, CoreInterface $core)
	{
            return array('ok',array('date'=>date('d.m.Y')));
	}
    }