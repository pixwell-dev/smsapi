<?php

namespace NotificationChannels\Smsapi;

use SMSApi\Client;
use SMSApi\Proxy\Proxy;
use SMSApi\Api\MmsFactory;
use SMSApi\Api\SmsFactory;
use SMSApi\Api\VmsFactory;
use SMSApi\Api\Response\Response;

class SmsapiClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @param Client     $client
     * @param array      $defaults
     * @param Proxy|null $proxy
     */
    public function __construct(Client $client, array $defaults = [], Proxy $proxy = null)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->proxy = $proxy;
    }

    /**
     * @param  SmsapiMessage $message
     * @return Response
     */
    public function send(SmsapiMessage $message)
    {
        if ($message instanceof SmsapiSmsMessage) {
            return $this->sendSms($message);
        } elseif ($message instanceof SmsapiMmsMessage) {
            return $this->sendMms($message);
        } elseif ($message instanceof SmsapiVmsMessage) {
            return $this->sendVms($message);
        }
    }

    /**
     * @param  SmsapiSmsMessage $message
     * @return Response
     */
    public function sendSms(SmsapiSmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $sms = (new SmsFactory($this->proxy, $this->client))->actionSend();
        if (isset($data['content'])) {
            $sms->setText($data['content']);
        }
        if (isset($data['template'])) {
            $sms->setTemplate($data['template']);
        }
        if (isset($data['to'])) {
            $sms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $sms->setGroup($data['group']);
        }
        if (isset($data['sms']['from'])) {
            $sms->setSender($data['sms']['from']);
        }
        if (isset($data['sms']['fast'])) {
            $sms->setFast($data['sms']['fast']);
        }
        if (isset($data['sms']['flash'])) {
            $sms->setFlash($data['sms']['flash']);
        }
        if (isset($data['sms']['encoding'])) {
            $sms->setEncoding($data['sms']['encoding']);
        }
        if (isset($data['sms']['normalize'])) {
            $sms->setNormalize($data['sms']['normalize']);
        }
        if (isset($data['sms']['nounicode'])) {
            $sms->setNoUnicode($data['sms']['nounicode']);
        }
        if (isset($data['sms']['single'])) {
            $sms->setSingle($data['sms']['single']);
        }
        if (isset($data['date'])) {
            $sms->setDateSent($data['date']);
        }
        if (isset($data['common']['notify_url'])) {
            $sms->setNotifyUrl($data['common']['notify_url']);
        }
        if (isset($data['common']['partner'])) {
            $sms->setPartner($data['common']['partner']);
        }
        if (isset($data['common']['test'])) {
            $sms->setTest($data['common']['test']);
        }

        return $sms->execute();
    }

    /**
     * @param  SmsapiMmsMessage $message
     * @return Response
     */
    public function sendMms(SmsapiMmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $mms = (new MmsFactory($this->proxy, $this->client))->actionSend();
        $mms->setSubject($data['subject']);
        $mms->setSmil($data['smil']);
        if (isset($data['to'])) {
            $mms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $mms->setGroup($data['group']);
        }
        if (isset($data['date'])) {
            $mms->setDateSent($data['date']);
        }
        if (isset($data['common']['notify_url'])) {
            $mms->setNotifyUrl($data['common']['notify_url']);
        }
        if (isset($data['common']['partner'])) {
            $mms->setPartner($data['common']['partner']);
        }
        if (isset($data['common']['test'])) {
            $mms->setTest($data['common']['test']);
        }

        return $mms->execute();
    }

    /**
     * @param  SmsapiVmsMessage $message
     * @return Response
     */
    public function sendVms(SmsapiVmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $vms = (new VmsFactory($this->proxy, $this->client))->actionSend();
        if (isset($data['file'])) {
            $vms->setFile($data['file']);
        }
        if (isset($data['tts'])) {
            $vms->setTts($data['tts']);
            if (isset($data['tts_lector'])) {
                $vms->setTtsLector($data['tts_lector']);
            }
        }
        if (isset($data['to'])) {
            $vms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $vms->setGroup($data['group']);
        }
        if (isset($data['vms']['from'])) {
            $vms->setFrom($data['vms']['from']);
        }
        if (isset($data['vms']['tries'])) {
            $vms->setTry($data['vms']['tries']);
        }
        if (isset($data['vms']['interval'])) {
            $vms->setInterval($data['vms']['interval']);
        }
        if (isset($data['date'])) {
            $vms->setDateSent($data['date']);
        }
        if (isset($data['common']['notify_url'])) {
            $vms->setNotifyUrl($data['common']['notify_url']);
        }
        if (isset($data['common']['partner'])) {
            $vms->setPartner($data['common']['partner']);
        }
        if (isset($data['common']['test'])) {
            $vms->setTest($data['common']['test']);
        }

        return $vms->execute();
    }
}
