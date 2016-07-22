<?php

/**
 * 
 */
class MQNotifyProcessor extends BaseProcessor implements ProcessorInterface
{
    public function run($data, CoreInterface $core)
    {
        // check empty params
        if (empty($data['notification'])) {
            $core->getLogger()->log('Empty "notification" key in input data. Notification aborted.');
            return array('bad-params');
        }

        $NtfnManager = $core->getModule('NotificationManager');
        $sent = $NtfnManager->sendNotification($data['notification']);

        if ($sent) {
            return array('ok');
        } else {
            return array('failed');
        }
    }
}