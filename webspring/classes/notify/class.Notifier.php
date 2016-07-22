<?php

/**
 * Notifier
 *
 * Class for adding (only) notification to RabbitMQ.
 * It's also responsible for checking whether events & recipients are specified before adding notification to queue.
 *
 * See "handle-notifications-queue" exec path.
 * There you will see how notification is retrieved from the queue and sent to the recipients .
 * Classes responsible for fetching AMQP notifications from queue are:
 * NotificationManager              - module class, handling recipients
 * AbstractNotificationRecipient    - parent class for each recipient class e.g. EyeNotificationRecipient
 *                                      with own ->send() logic
 *
 * AMQP message format for notifications queue:
 * Queue name - "notification"
 * Message:
 * {
 *  date: 1534534344                        // (REQUIRED) UNIX time() of event
 *  eventname: 'init',                      // (REQUIRED) event name from config "notification.events" in "app/config/{current}/notifications,php"
 *  eventdata: {                            // (REQUIRED) Data for recipients, Keys and values will be joined into request string
 *              msisdn: 79171122245,        // Subscriber phone number
 *              arbitrary:'data',
 *              ...
 *              }
 *  direct: 'eye',                          // (OPTIONAL) If specified then NotificationManager module
 *                                          //            will route this notification to specified recipient
 *  restrictrecipients: ['eye', 'emms']     // (OPTIONAL) Restrict event recipients list (see NotificationManager::sendNotification())
 * }
 *
 * @see NotificationManager
 * @see AbstractNotificationRecipient
 */
class Notifier extends Linkable
{
    /**
     * Recipients
     *
     * Array from config "notification.recipients" in "app/config/{current}/notifications,php"
     * @var array $recipients Recipients defined in "notifications.php" config file 
     */
    protected $recipients;

    /**
     * Events
     *
     * Array from config "notification.events" in "app/config/{current}/notifications,php"
     * It defines recipients of each event by event name.
     * @var array $events Recipients defined in "notifications.php" config file
     */
    protected $events;

    /**
     * $notifnQueue
     *
     * @var RabbitQueue $notifnQueue WebSpring wrapper for AMQPQueue, representing notifications queue
     */
    protected $notifnQueue;

    /**
     * @var RedisQueue
     */
    protected $redisTransport;
    
    /*
     // Core is unreachable in __construct() use setup()
     public function __construct()
    {
        $this->recipients = $this->core->getConfig()->get('notifications.recipients');
        $this->events = $this->core->getConfig()->get('notifications.events');

        $rabbManager = $this->core->getModule('RabbitManager');
        $this->notifnQueue = $rabbManager->getQueue('notification');
    }
    */


    /**
     * Notifier::setup()
     *
     * Function that used instead of __construct
     * when instance created via ConfigInjector dependency injection
     *
     * Classes which extends "Linkable" takes a core object right after creating.
     * After that ->setup() is calling, where you can access $this->core
     * @see ConfigInjector::getClass()
     */
    public function setup()
    {
        // ----- SETUP CONFIGS -----
        $this->recipients = $this->core->getConfig()->get('notifications.recipients');
        $this->events = $this->core->getConfig()->get('notifications.events');

        if ($this->core->getConfig()->get('notifications.queuesource')=='redis') {
            $this->redisTransport = $this->core->getRedisClient();
        } else {
            $rabbManager = $this->core->getModule('RabbitManager');
            $this->notifnQueue = $rabbManager->getQueue('notification');
        }
    }



    /**
     * Notifier::notify()
     *
     * Main method for adding notification to Rabbit Queue.
     *
     * EXAMPLE of usage:
     * $core->getNotifier()->notify(
     *                      array(
     *                          'event'     =>'init',                         // (REQUIRED) Event name
     *                          'data'      =>array(                          // (REQUIRED) Data for recipients, Keys and values will be joined into request string
     *                                           'msisdn'=>79171122245,       // Subscriber phone number (int)
     *                                           'another'=>'data',           // (OPTIONAL) Another Data
     *                                           ...
     *                                           ),
     *                          'direct'    =>'eye',                          // (OPTIONAL) direct notification of given recipient
     *                          'recipients'=>array('eye', 'emms')            // (OPTIONAL) Array restricting recipients list
     *                      )
     *                  );
     *
     * @param array $params Array with notification params: event name, recipient, event data: msisdn... etc.
     * @return bool Notification succsess.
     * @see Notifier::notifyAll()
     * @see Notifier::notifyDirect()
     */
    public function notify(array $params)
    {

        if (empty($params)) {
            $this->core->getLogger()->log('Empty params. Notification aborted.');
            return false;
        }
        if (empty($params['event'])) {
            $this->core->getLogger()->log('Empty event name. Notification aborted. Data: '.var_export($params, true));
            return false;
        }
        // IF Direct Notification
        if (!empty($params['direct'])) {
            return $this->notifyDirect(
                $params['event'],
                $params['direct'],
                (empty($params['data']) ? array() : $params['data'])
            );
        } else { // Common notification
            return $this->notifyAll(
                $params['event'],
                (empty($params['data']) ? array() : $params['data']),
                (empty($params['recipients']) ? array() : $params['recipients'])
            );
        }

    }


    /**
     * Notifier::notifyAll()
     *
     * Adding notification with arbitrary data to Rabbit Queue.
     * $eventName is a key from "events" array in "app/config/{current}/notifications.php" config file
     * $eventdata is arbitrary data array to send with notification.
     * It will be transmitted to recipient classes,
     * specified in "recipients" section as "handler"
     *
     * EXAMPLE of usage:
     * $core->getNotifier()->notifyAll(
     *                      'init',                         // 1. (REQUIRED) Event name
     *                      array(                          // 2. (REQUIRED) Event Data array
     *                            'msisdn'=>79171122245,    // Subscriber phone number (int)
     *                            'another'=>'data',
     *                             ...
     *                            )
     *                      array('eye', 'emms')            // 4. (OPTIONAL) Array restricting recipients list
     *                 );
     *
     * If last {$restrict} argument will be given, then each recipient for {$eventName} event will be notified
     * ONLY in case when it specified in {$restrict} array. This logic is implemented in NotificationManager module.
     *
     * @param string $eventName Event name from config "notifications.events"
     * @param array $eventdata arbitrary data array for recipient class
     * @param array $restrict array with recipitn names that will restrict list of recipients specified for that event
     * @return bool Whether notification added to queue or not.
     */
    public function notifyAll($eventName, array $eventdata=array(), array $restrict=array())
    {
        //------------- CHECK DATA--------------

        if (empty($eventName)) {
            $this->core->getLogger()->log('Empty event name. Notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        // Event not specified.
        if (!isset($this->events[$eventName])) {
            $this->core->getLogger()->log('Event "'.$eventName.'" is not declared. Notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        // Empty event recipients.
        if (!isset($this->events[$eventName]['recipients']) || empty($this->events[$eventName]['recipients'])) {
            $this->core->getLogger()->log('There is no recipients for "'.$eventName.'" event. Notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        // Empty 
        // check whether all recipients for current event are defined in configuration or not (log if not)
        $recipientsLen = count($this->events[$eventName]['recipients']);
        foreach ($this->events[$eventName]['recipients'] as $recipientName) {
            if (empty($this->recipients[$recipientName])) {
                $this->core->getLogger()->log('Recipient "'.$recipientName.'" is not declared. But it is specified for event "'.$eventName.'" in "notifications.php" config file. Data: '.var_export($eventdata, true));
                $recipientsLen--;
            }
        }
        // if all recipients for this event are not defined in config "recipients" section then abort
        if ($recipientsLen == 0) {
            $this->core->getLogger()->log('All recipients for event "'.$eventName.'" are not declared in "notifications.php" config file. Notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        //------------- /check data--------------


        //------------- ADD TO RabbitMQ --------------
        // -COLLECT DATA-
        $queueData = array(
            'date'          =>time(),
            'eventname'     =>$eventName,
            'eventdata'     =>$eventdata
        );
        if (!empty($restrict)) { // if we want to restrict list of recipients, save it here
            $queueData['restrictrecipients'] = $restrict;
        }
        // -/collect data-

        if ($this->core->getConfig()->get('notifications.queuesource')=='redis') {
            $queued = $this->redisTransport->rpush('notification_queue',json_encode($queueData));
        } else {
            $queued = $this->notifnQueue->push($queueData);
        }

        if (!$queued) {
            $this->core->getLogger()->log('Something wrong... Event "'.$eventName.'" is not queued. Data: '.var_export($eventdata, true));
            return false;
        }

        $this->core->getLogger()->log('Notification queued. Data: '.var_export($eventdata, true));
        return true;
    }


    /**
     * Notifier::notifyDirect()
     *
     * Same as Notifier::notify() but for direct notification of given recipient about given event.
     *
     * EXAMPLE of usage:
     * $core->getNotifier()->notifyDirect('tele2tss', 'confirm', array('msisdn'=>79171122245, 'some'=>'data', 'here'));
     *
     * While Notifier::notify() used by each service to spread notification
     * between several recipients ('eye', 'cyka' etc.) at the moment,
     * this method may be usefull for exaple for "Eye of Sauron" core,
     * to notificate one specific service about something.
     *
     * Direct notification happens without checking
     * whether given $recipientName specified for given $eventName as recipient or not
     * in "events.recipients" section of "app/config/{current}/notifications.php" file.
     *
     * @param string $eventName     Is a key from "events" array in "app/config/{current}/notifications.php"
     * @param string $recipientName Is a key from "recipients" array in "app/config/{current}/notifications.php"
     * @param array $eventdata      Data to send
     * @return bool                 Added to queue
     * @see Notifier::notify()
     */
    public function notifyDirect($eventName, $recipientName, array $eventdata=array())
    {
        /**
         * check Recipient:
         * - it's not empty?
         * - it's defined in "recipients" array in "app/config/{current}/notifications.php" ?
         */
        if (empty($recipientName)) {
            $this->core->getLogger()->log('Empty recipient name. Direct notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        if (empty($this->recipients[$recipientName])) {
            $this->core->getLogger()->log('Recipient "'.$recipientName.'" is not declared. Direct notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }

        /**
         * check Event
         * - empty?
         * - defined?
         */
        if (empty($eventName)) {
            $this->core->getLogger()->log('Empty event name. Direct notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        if (!isset($this->events[$eventName])) {
            $this->core->getLogger()->log('Event "'.$eventName.'" is not declared. Direct notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }
        /*if (empty($msisdn)) {
            $this->core->getLogger()->log('Empty msisdn. Direct notification aborted. Data: '.var_export($eventdata, true));
            return false;
        }*/

        //--- Add to RabbitMQ ---
        // collect data
        $queueData = array(
            'date'      =>time(),
            'eventname' =>$eventName,
            'eventdata' =>$eventdata,
            'direct'    =>$recipientName
        );

        if ($this->core->getConfig()->get('notifications.queuesource')=='redis') {
            $queued = $this->redisTransport->rpush('notification_queue',json_encode($queueData));
        } else {
            $queued = $this->notifnQueue->push($queueData);
        }



        if (!$queued) {
            $this->core->getLogger()->log('Something wrong... Direct notification of "'.$recipientName.'" ("'.$eventName.'" event) is not queued. Data: '.var_export($eventdata, true));
            return false;
        }

        $this->core->getLogger()->log('Notification queued. Data: '.var_export($eventdata, true));
        return true;
    }


}