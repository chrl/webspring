<?php

/**
 * 
 */
class MQgetNotificationProcessor extends BaseProcessor implements ProcessorInterface
{
    public function run($data, CoreInterface $core)
    {

        if ($core->getConfig()->get('notifications.queuesource') == 'redis') {
            $redis = $core->getRedisClient();
        } else {
            $rabbManager = $core->getModule('RabbitManager');
            $notificationQueue = $rabbManager->getQueue('notification');
        }


        $core->getLogger()->log('Trying to get next notification from queue...');
        /**
         * array || null
         */

        if (isset($redis)) {
            $nextNotification = json_decode($redis->lpop('notification_queue'),true);
        } else  $nextNotification = $notificationQueue->get();

        if (!empty($nextNotification)) {

            $core->getLogger()->log('Got next notification from queue: '.var_export($nextNotification,true));
            // confirm the message
            if (!isset($redis)) $notificationQueue->ack();

            return array('ok', array('notification' => $nextNotification));
        } else {
            $core->getLogger()->log('There is no notifications in queue.');
            return array('empty');
        }
    }
}