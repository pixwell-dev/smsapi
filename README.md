# Smsapi notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/smsapi.svg)](https://packagist.org/packages/laravel-notification-channels/smsapi)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/smsapi/master.svg)](https://travis-ci.org/laravel-notification-channels/smsapi)
[![StyleCI](https://styleci.io/repos/89257474/shield)](https://styleci.io/repos/89257474)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/smsapi.svg)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsapi)
[![Code Coverage](https://scrutinizer-ci.com/g/laravel-notification-channels/smsapi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsapi/?branch=master)
[![Total Downloads](https://poser.pugx.org/laravel-notification-channels/smsapi/downloads)](https://packagist.org/packages/laravel-notification-channels/smsapi)
[![PHP 7 ready](http://php7ready.timesplinter.ch/laravel-notification-channels/smsapi/badge.svg)](https://travis-ci.org/laravel-notification-channels/smsapi)

This package makes it easy to send notifications using [Smsapi](https://www.smsapi.pl/) with Laravel 5.5+, 6.x, & 7.x

## Contents

- [Installation](#installation)
    - [Setting up the Smsapi service](#setting-up-the-smsapi-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/smsapi
```

You can also publish the config file with:

```bash
php artisan vendor:publish --provider="NotificationChannels\Smsapi\SmsapiServiceProvider"
```

### Setting up the Smsapi service

Log in to your [Smsapi dashboard](https://ssl.smsapi.pl/) and configure your preferred authentication method.
Set your credentials and defaults in `config/smsapi.php`:

```php
'auth' => [
    'method' => 'token',
    // 'method' => 'password',
    'credentials' => [
        'token' => env('SMSAPI_AUTH_TOKEN'),
        // 'username' => env('SMSAPI_AUTH_USERNAME'),
        // 'password' => env('SMSAPI_AUTH_PASSWORD'), // Hashed by MD5
    ],
],
'defaults' => [
    'common' => [
        // 'notify_url' => env('SMSAPI_NOTIFY_URL'),
        // 'partner' => env('SMSAPI_PARTNER'),
        // 'test' => env('SMSAPI_TEST', true),
    ],
    'sms' => [
        // 'from' => env('SMSAPI_FROM'),
        // 'fast' => false,
        // 'flash' => false,
        // 'encoding' => 'utf-8',
        // 'normalize' => false,
        // 'nounicode' => false,
        // 'single' => false,
    ],
    'mms' => [
    ],
    'vms' => [
        // 'from' => env('SMSAPI_FROM'),
        // 'tries' => 2,
        // 'interval' => 300,
        // 'tts_lector' => 'Agnieszka',
        // 'skip_gsm' => false,
    ],
],
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Smsapi\SmsapiChannel;
use NotificationChannels\Smsapi\SmsapiSmsMessage;

class FlightFound extends Notification
{
    public function via($notifiable)
    {
        return [SmsapiChannel::class];
    }

    public function toSmsapi($notifiable)
    {
        return (new SmsapiSmsMessage())->content("Buy now your flight!");
    }
}
```

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Smsapi\SmsapiChannel;
use NotificationChannels\Smsapi\SmsapiMmsMessage;

class AnimalTrespassed extends Notification
{
    public $photoId;

    public function via($notifiable)
    {
        return [SmsapiChannel::class];
    }

    public function toSmsapi($notifiable)
    {
        return (new SmsapiMmsMessage())->subject('Animal!')->smil($this->smil());
    }

    private function smil()
    {
        $url = route('photos', ['id' => $this->photoId]);
        $smil =
            "<smil>" .
                "<head>" .
                    "<layout>" .
                        "<root-layout height='100%' width='100%'/>" .
                        "<region id='Image' width='100%' height='100%' left='0' top='0'/>" .
                    "</layout>" .
                "</head>" .
                "<body><par><img src='{$url}' region='Image' /></par></body>" .
            "</smil>";
        return $smil;
    }
}
```

Add a `routeNotificationForSmsapi` method to your Notifiable model to return the phone number(s):

```php
public function routeNotificationForSmsapi()
{
    return $this->phone_number;
}
```

Or add a `routeNotificationForSmsapiGroup` method to return the contacts group:

```php
public function routeNotificationForSmsapiGroup()
{
    return $this->contacts_group;
}
```

### Available Message methods

#### SmsapiSmsMessage

- `to(string|string[] $to)`
- `group(string $group)`
- `content(string $content)`
- `template(string $template)`
- `from(string $from)`
- `fast(bool $fast = true)`
- `flash(bool $flash = true)`
- `encoding(string $encoding)`
- `normalize(bool $normalize = true)`
- `nounicode(bool $nounicode = true)`
- `single(bool $single = true)`
- `date(int|string $date)`
- `notifyUrl(string $notifyUrl)`
- `partner(string $partner)`
- `test(bool $test = true)`

#### SmsapiMmsMessage

- `to(string|string[] $to)`
- `group(string $group)`
- `subject(string $subject)`
- `smil(string $smil)`
- `date(int|string $date)`
- `notifyUrl(string $notifyUrl)`
- `partner(string $partner)`
- `test(bool $test = true)`

#### SmsapiVmsMessage

- `to(string|string[] $to)`
- `group(string $group)`
- `file(string $file)`
- `tts(string $tts)`
- `ttsLector(string $ttsLector)`
- `from(string $from)`
- `tries(int $tries)`
- `interval(int $interval)`
- `skipGsm(bool $skipGsm = true)`
- `date(int|string $date)`
- `notifyUrl(string $notifyUrl)`
- `partner(string $partner)`
- `test(bool $test = true)`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mat.drost@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mateusz Drost](https://github.com/mdrost)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
