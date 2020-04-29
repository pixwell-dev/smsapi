<?php

namespace NotificationChannels\Smsapi;

use NotificationChannels\Smsapi\Exceptions\CouldNotSendNotification;
use Smsapi\Client\Feature\Mms\Bag\SendMmsBag;
use Smsapi\Client\Feature\Mms\Data\Mms;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Feature\Sms\Data\Sms;
use Smsapi\Client\Service\SmsapiComService;
use Smsapi\Client\Service\SmsapiPlService;

class Smsapi
{
    /** @var SmsapiPlService|SmsapiComService */
    protected $service;

    /** @var SmsapiConfig */
    public $config;

    public function __construct($service, SmsapiConfig $config)
    {
        $this->service = $service;
        $this->config = $config;
    }

    /**
     * @param \NotificationChannels\Smsapi\SmsapiMessage $message
     * @param string|null $to
     *
     * @return \Smsapi\Client\Feature\Mms\Data\Mms|\Smsapi\Client\Feature\Sms\Data\Sms
     * @throws \NotificationChannels\Smsapi\Exceptions\CouldNotSendNotification
     */
    public function sendMessage(SmsapiMessage $message, ?string $to)
    {
        if ($message instanceof SmsapiSmsMessage) {
            return $this->sendSmsMessage($message, $to);
        }

        if ($message instanceof SmsapiMmsMessage) {
            return $this->sendMmsMessage($message, $to);
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }

    /**
     * @param \NotificationChannels\Smsapi\SmsapiSmsMessage $message
     * @param string|null $to
     *
     * @return \Smsapi\Client\Feature\Sms\Data\Sms
     */
    protected function sendSmsMessage(SmsapiSmsMessage $message, ?string $to): Sms
    {
        $sms = SendSmsBag::withMessage($to, trim($message->content));
        $sms->test = $this->config->isTest();

        $this->fillOptionalParams($sms, $message, $this->config->getSmsDefaultOptions());

        return $this->service->smsFeature()->sendSms($sms);
    }

    /**
     * @param \NotificationChannels\Smsapi\SmsapiMmsMessage $message
     * @param string|null $to
     *
     * @return \Smsapi\Client\Feature\Mms\Data\Mms
     */
    protected function sendMmsMessage(SmsapiMmsMessage $message, ?string $to): Mms
    {
        $mms = new SendMmsBag($to, $message->subject, $message->content);
        $mms->test = $this->config->isTest();

        return $this->service->mmsFeature()->sendMms($mms);
    }

    /**
     * @param SendSmsBag|SendMmsBag $bag
     * @param SmsapiMessage $message
     * @param array $optionalParams
     *
     * @return Smsapi
     */
    protected function fillOptionalParams(&$bag, $message, $defaults)
    {
        foreach ($defaults as $key => $value) {
            $bag->$key = $value;
        }

        foreach ($message->getFilledParams() as $param) {
            if (property_exists($message, $param)) {
                $bag->$param = $message->$param;
            }
        }

        return $this;
    }
}