<?php

namespace NotificationChannels\Smsapi;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Smsapi\Exceptions\CouldNotSendNotification;

class SmsapiChannel
{
    /**
     * @var Smsapi
     */
    protected $smsapi;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @param Smsapi $smsapi
     */
    public function __construct(Smsapi $smsapi, Dispatcher $events)
    {
        $this->smsapi = $smsapi;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed
     * @throws Exception
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toSmsapi($notifiable);

            if (is_string($message)) {
                $message = new SmsapiSmsMessage($message);
            }

            if (!$message instanceof SmsapiMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->smsapi->sendMessage($message, $to);
        } catch (Exception $exception) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'smsapi',
                ['message' => $exception->getMessage(), 'exception' => $exception]
            );

            $this->events->dispatch($event);

            throw $exception;
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('smsapi')) {
            return $notifiable->routeNotificationFor('smsapi');
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
