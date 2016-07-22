<?php

/**
 * AbstractNotificationRecipient
 * 
 */
abstract class AbstractNotificationRecipient extends Linkable implements NotificationRecipientInterface, LinkableInterface
{

    /**
     * Recipient Name
     * 'emms', 'eye', 'cyka'...
     * @var string $name Recipient name from "recipients" section in "app/config/{current}/notifications.php" config file
     */
    protected $name;

    /**
     * Recipient params
     * these params are from config file "app/config/{current}/notifications.php"
     * from section "recipients".
     * @var array Assoc. array with Recipient configuration (host, path...)
     */
    protected $params;

    // Core is unreachable in __construct() use setup()
    /*public function __construct(array $params)
    {
        $this->params = $params;

    }*/
    
    public function setup()
    {
        $this->params = $this->core->getConfig()->get('notifications.recipients')[$this->name]['params'];
    }

    /**
     * AbstractNotificationRecipient::send()
     * 
     * Gets event name and data, checks if there is method [eventname]PrepareData() defined for given event.
     * If defined, then call [eventname]PrepareData($data) to modify data before transmitting notification to external Recipient.
     *
     * @param string $eventName event name. 'init', 'subscription'...
     * @param int $eventDate UNIX time() of event
     * @param array $data Data for sending to each Recipient
     */
    public function send($eventName, $eventDate, array $data = array())
    {
        if (empty($eventName)) {
            $this->core->getLogger()->log('Empty event name given. Do nothing. Data: '.var_export($data, true));
            return false;
        }

        // --- PREPARE NOTIFICATION DATA ---
        $ntfnData = array(
            'project_id'=>$this->core->getConfig()->get('settings.projectid'),
            'event'=>$eventName,
            'date'=>$eventDate
        );
        
        $prepareMethodName = $eventName.'PrepareData';
        if (method_exists($this, $prepareMethodName)) {
            //$this->core->getLogger()->log('Preparing data for '.$eventName.'event using '.$prepareMethodName.'()... Raw Data: '.var_export($data, true));
            $data = $this->$prepareMethodName($data);
            $this->core->getLogger()->log('Data prepared for '.$eventName.' event using '.$prepareMethodName.'(). Data: '.var_export($data, true));
        }

        $transmitData = array_merge($ntfnData, $data);
        // --- /prepare notification data ---
        
        
        $this->core->getLogger()->log('Transmiting Data to "'.$this->name.'" server...');


        return $this->transmit($transmitData);
    }
}