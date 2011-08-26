<?php

    /**
     * GetAphorism
     * 
     * @package HoroSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class GetAphorismProcessor extends BaseProcessor implements ProcessorInterface
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
	    $aphor=file_get_contents('http://api.forismatic.com/api/1.0/?method=getQuote&format=json');
            $aphor = json_decode($aphor);
            
            return array('ok',array('aphorism'=>$aphor->quoteText,'author'=>$aphor->quoteAuthor));
	}
    }