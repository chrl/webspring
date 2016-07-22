<?php

/**
 * NotificationRecipientInterface
 *
 * Interface for notification Recipient such as 'Eye', 'CYKA', 'Emms' etc.
 */
interface NotificationRecipientInterface
{

    /**
     * NotificationRecipientInterface::transmit()
     *
     * Common method for sending notification to external recipients.
     * Each recipient class (for example EyeNotificationResource) will implement this method with own logic.
     * It gives ability to use unique way to send notification for each recipient server (curl, socket, with authorisation...)
     *
     * Classes implementing this interface has ->send() method, where $data array is preparing for each type of event.
     *
     * Estimated response format for notifications is a JSON: {"status":"ok"|"fail","message":"notification ..."}
     *
     * @param array $data Notification data with all parameters for converting into the query
     * @return mixed
     * @see AbstractNotificationRecipient::send()
     */
    public function transmit(array $data);


    /**
     * Method to close all connections and clean workflow
     * on NotificationManager Module outro()
     * @return mixed
     */
    public function shutDown();

}