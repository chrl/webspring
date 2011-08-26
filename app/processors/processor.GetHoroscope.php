<?php

    /**
     * GetHoroscope
     * 
     * @package HoroSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetHoroscopeProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * GetHoroscope::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    $horo=file_get_contents('http://horoscope.ra-project.net/api/'.$core->getRequest()->get('signId'));
            
            $horo = strstr($horo, '<text>');
            $horo = str_replace('<text>','',$horo);
            $horo = str_replace(strstr($horo,'</text>'),'',$horo);
            
            return array('ok',array('horoscope'=>$horo));
	}
    }