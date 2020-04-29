<?php

declare(strict_types=1);

namespace NotificationChannels\Smsapi\Exceptions;

class InvalidConfigException extends \Exception
{
    public static function missingConfig(): self
    {
        return new self('Missing config. You must set the auth token and service');
    }
}