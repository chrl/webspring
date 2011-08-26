<?php

    /**
     * DoUselessThingsProcessor
     * 
     * @package   
     * @author 
     * @copyright admin
     * @version 2011
     * @access public
     */
    class DoUselessThingsProcessor extends BaseProcessor implements ProcessorInterface
    {
	/**
	 * DoUselessThingsProcessor::run()
	 * 
	 * @param mixed $data
	 * @param mixed $core
	 * @return
	 */
	public function run($data, CoreInterface $core)
	{
	    $uselessString = 'abcdefghijklmnopqrstuvwxyz';
            for ($x=0;$x<$data['iterate'];$x++) {
                
                for ($z=0;$z<100;$z++) {
                
                    for ($y=0;$y<strlen($uselessString);$y++) {

                        $position = rand(0,strlen($uselessString)-1);
                        $tmp = $uselessString[$y];
                        $uselessString[$y] = $uselessString[$position];
                        $uselessString[$position] = $tmp;
                    }
                }
            }
            return array('ok',array('string'=>$uselessString));
	}
    }