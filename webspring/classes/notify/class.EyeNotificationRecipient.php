<?php

/**
 * EyeNotificationRecipient
 *
 * Class responsible for send notifications to "Eye of Saurone"
 * (main new platform collecting data from all micro-services)
 */
class EyeNotificationRecipient extends AbstractNotificationRecipient implements NotificationRecipientInterface
{
    protected $name = 'eye';
    
    public function transmit(array $data)
    {
        // check on empty data
        if (empty($data)) {
            $this->core->getLogger()->log('Empty data given. Notification transmition aborted.');
            return false;
        }

        $this->core->getLogger()->log('Transmit data: '.var_export($data, true));

        // transmition logic for Eye recipient here
        // check event not empty

        $answer = file_get_contents($this->params['path'].'?'.http_build_query($data));
        // assumed OK answer is a JSON: {"status":"ok","message":"notification accepted"}
        $answerJson = json_decode($answer);
        if (empty($answerJson->status)) {
            $this->core->getLogger()->log('Failed. Bad JSON response format: '.$answer);
            return false;
        } else {
            if ($answerJson->status == 'ok') {
                $this->core->getLogger()->log('Response is OK. Notification accepted.');
                return true;
            } else {
                $this->core->getLogger()->log('Notification failed. Response is NOT OK: '.$answer);
                return false;
            }
        }
    }
    
    public function shutDown()
    {
        // nothing to do...
    }
}