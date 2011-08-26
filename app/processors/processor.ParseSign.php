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
    class ParseSignProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * GetHoroscope::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
        
        protected $signs = array (
            1 => 'aries',
            2 => 'taurus',
            3 => 'gemini',
            4 => 'cancer',
            5 => 'leo',
            6 => 'virgo',
            7 => 'libra',
            8 => 'scorpio',
            9 => 'saggitarium',
            10 => 'capricorn',
            11 => 'aquarius',
            12 => 'pisces',
        );
        
        
        
	public function run($data, CoreInterface $core)
	{
	    $sign = $core->getRequest()->get('sign');
            
            if(!in_array($sign,$this->signs)) {
                return array('fail');
            }
            
            return array('ok',array('signId'=>array_search($sign,$this->signs)));
	}
    }