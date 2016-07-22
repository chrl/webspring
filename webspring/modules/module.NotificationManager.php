<?php

/**
 * NotificationManagerModule
 *
 * Responsible for selecting target event recipient class and
 * calling his ->send() method.
 */
class NotificationManagerModule extends BaseModule implements ModuleInterface
{
    protected $recipients;

    protected $events;

    /**
     * Implementation of ModuleInterface::intro()
     * It's play a role of __construct() for Module classes.
     * @param CoreInterface $core
     */
    public function intro(CoreInterface $core)
    {
        $this->linkCore($core);
        // prepare config data
        $this->events = $core->getConfig()->get('notifications.events');
        // create recipient instances
        foreach ($core->getConfig()->get('notifications.recipients') as $recipientName => $recipientParams) {
            $getClassMethod = 'get'.$recipientParams['class'];
            $this->recipients[$recipientName] = $core->$getClassMethod();
        }
        return $this;
    }
    /**
     * Implementation of ModuleInterface::outro()
     * It's responcible for clearing workflow.
     * @param CoreInterface $core
     */
    public function outro(CoreInterface $core) {
        foreach ($this->recipients as $recipientObj) {
            $recipientObj->shutDown();
        }
        return $this;
    }

    /**
     * NotificationManager::sendNotification()
     *
     * Selects recipients for $data["eventname"] and call ->send() method for each Recipient object
     * if 'direct' property is specified then call ->send() directly
     *
     * @param array $data Full message from AMQP queue. See assumed format in Notifier class description
     * @see Notifier
     * @see Notifier::notify()
     */
    public function sendNotification(array $data)
    {
        
        // check event
        if (empty($data['eventname'])) {
            $this->core->getLogger()->log('Empty event name given. Notification aborted. Data: '.var_export($data, true));
            return false;
        }
        // event defined?
        if (!isset($this->events[$data['eventname']])) {
            $this->core->getLogger()->log('Event "'.$data['eventname'].'" is not defined in notifications.php config file. Notification aborted. Data: '.var_export($data, true));
            return false;
        }
        // check data (only notice)
        if (empty($data['eventdata'])) {
            $this->core->getLogger()->log('Notice: Empty data given for event "'.$data['eventname'].'"');
        }

        // --------------- CHECK DIRECT RECIPIENT -----------------
        if (!empty($data['direct'])) {
            $sent = false;
            if (!empty($this->recipients[$data['direct']])) {
                //---------------SEND DIRECT--------------
                $sent = $this->recipients[$data['direct']]->send($data['eventname'], $data['date'], $data['eventdata']);
                //---------------/send direct-------------
                $this->core->getLogger()->log('Direct notification was'.($sent ? '' : ' NOT').' sent to "'.$data['direct'].'".');
            } else {
                $this->core->getLogger()->log('Direct recipient "'.$data['direct'].'" is not defined in notifications.php config file.');
            }
            return $sent;
        }
        // --------------- /check direct recipient -----------------

        // notify about restricting recipients list
        $hasRestricting = false;
        if (!empty($data['restrictrecipients']) && is_array($data['restrictrecipients'])) {
            $hasRestricting = true;
            $this->core->getLogger()->log('Recipients list for event "'.$data['eventname'].'" will be restricted by following: '.join(', ', $data['restrictrecipients']));
        }
        // send to all recipients
        $count = count($this->events[$data['eventname']]['recipients']);    // recipients count 
        $failed = array();                                                  // failed recipients
        $ok = array();                                                      // success recipient
        $skipped = array();                                                 // skipped recipients
        foreach ($this->events[$data['eventname']]['recipients'] as $recipientName) {
            // if this recipient is not specified in the $data['restrictrecipients'] - continue
            if ($hasRestricting && !in_array($recipientName, $data['restrictrecipients'])) {
                $skipped[] = $recipientName;
                continue;
            }

            if (!empty($this->recipients[$recipientName])) {
                //---------------SEND---------------
                $this->recipients[$recipientName]->send($data['eventname'], $data['date'], $data['eventdata']) ? $ok[] = $recipientName : $failed[] = $recipientName;
                //---------------/send--------------
            } else {
                $this->core->getLogger()->log('Recipient "'.$recipientName.'" specified for event "'.$data['eventname'].'" is not defined in "recipients" section of notifications.php config file.');
                $failed[] = $recipientName;
            }
        }

        // all skiped - it's OK because "restrictrecipients" was added consciously
        if ($count == count($skipped)) {
            // all skiped
            $this->core->getLogger()->log('All recipients were skipped because they are not in "restrictrecipients" list: "'.join(', ', $data['restrictrecipients']).'" not contain them.');
            return true;
        }

        // if there is no recipient with TRUE response - it's bad.
        if (empty($ok)) {
            $this->core->getLogger()->log('All recipients returned FAIL while sending notification. Data: '.var_export($data, true));
            return false;
        }

        $this->core->getLogger()->log('Notification sent: OK('.count($ok).'): "'.join(', ', $ok).'" FAIL('.count($failed).'): "'.join(', ', $failed).'" SKIPPED('.count($skipped).'): "'.join(', ', $skipped).'". Data: '.var_export($data, true));

        return true;
    }


}