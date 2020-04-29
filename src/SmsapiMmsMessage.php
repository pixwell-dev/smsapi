<?php

namespace NotificationChannels\Smsapi;

class SmsapiMmsMessage extends SmsapiMessage
{
    /**
     * The MMS subject.
     *
     * @var string
     */
    public $subject;

    /**
     * @param string $subject
     *
     * @return self
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }
}
