<?php

namespace NotificationChannels\Smsapi;

use DateTimeInterface;

class SmsapiSmsMessage extends SmsapiMessage
{
    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $encoding;

    /**
     * @var array
     */
    public $idx;

    /**
     * @var bool
     */
    public $checkIdx;

    /**
     * @var string
     */
    public $partnerId;

    /**
     * @var DateTimeInterface
     */
    public $expirationDate;

    /**
     * @var bool
     */
    public $single;

    /**
     * @var bool
     */
    public $noUnicode;

    /**
     * @var bool
     */
    public $normalize;

    /**
     * @var string
     */
    public $notifyUrl;

    /**
     * @var bool
     */
    public $fast;

    /**
     * @var string
     */
    public $param1;

    /**
     * @var string
     */
    public $param2;

    /**
     * @var string
     */
    public $param3;

    /**
     * @var string
     */
    public $param4;
}
