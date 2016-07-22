<?php

/**
 * Class SendTele2SmsProcessor
 *
 * Unified processor for sending SMS via CYKA server.
 */
class SendSMSProcessor extends BaseProcessor implements ProcessorInterface
{
    protected $serviceId = 0;
    protected $operatorId = 0;
    protected $pushUrl = 'http://gateasg.a1-content.ru/cgi-bin/push.pl?';
    
    public function run($data, CoreInterface $core)
    {

        $num =          empty($data['msisdn'])      ? false : $data['msisdn'];          // client phone number
        $text =         empty($data['text'])        ? false : urldecode($data['text']); // SMS text
        $shortcode =    empty($data['shortcode'])   ? '' : $data['shortcode'];          // sender short code

        $serviceId = (empty($data['sendsmsconf']['serviceId']) || !is_numeric($data['sendsmsconf']['serviceId'])) ? $this->serviceId : (int)$data['sendsmsconf']['serviceId'];
        $operatorId = (empty($data['sendsmsconf']['operatorId']) || !is_numeric($data['sendsmsconf']['operatorId'])) ? $this->operatorId : (int)$data['sendsmsconf']['operatorId'];

        if ($num && $text){

            $query = array(
                    'service-id'	    => $serviceId,
                    'status'	        => 'notice',
                    'destination'	    => $num,
                    'plug'		        => $shortcode,
                    'data'	            => iconv('utf-8', 'cp1251', $text),
                    'force-operator-id' => $operatorId
            );


            /**
             * Using curl on PHP 7 causes segmentation fault.
             */
            /*
            $params = array(
                    CURLOPT_URL		        => $this->pushUrl.http_build_query($query),
                    CURLOPT_PORT		    => 80,
                    CURLOPT_RETURNTRANSFER	=> true,
                    CURLOPT_MAXREDIRS	    => 1,
                    CURLOPT_SSL_VERIFYPEER	=> false,
                    CURLOPT_TIMEOUT		    => 10,
                    CURLOPT_HTTPHEADER	    => array()
            );


            $ch = curl_init();
            curl_setopt_array($ch, $params);
            $answer = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            */

            $answer = file_get_contents($this->pushUrl.http_build_query($query));
            $core->getLogger()->log('Message sent. Response: '.$answer);
            //$core->getLogger()->log('Message sent CODE:'.$info['http_code'].' Response: '.$answer);

            $retArr = array('ok', $data);
            /*
            switch (true) {
                case ($info['http_code'] == 200):
                    $retArr = array('ok', $data);
                    break;
                case ($info['http_code'] >= 500):
                    $data['error_message'] = 'Service unavailable. Answer, code: '.$info['http_code'];
                    $retArr = array('service-unavailable', $data);
                    break;
                default:
                    $data['error_message'] = 'Invalid answer, code: '.$info['http_code'];
                    $retArr = array('fail', $data);
                    break;
            }
            */

            return $retArr;

        } else {

            return array('bad-params', array('error_message' => 'Invalid SMS params'));

        }
    }
}

