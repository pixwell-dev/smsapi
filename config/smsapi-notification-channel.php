<?php

return [

    'auth_token' => env('SMSAPI_AUTH_TOKEN'),

    /*
    |----------------------------------------------------------------------
    | SMSAPI service
    |----------------------------------------------------------------------
    |
    | Depending on which of SMSAPI service your account is using
    | Supported: "com", "pl".
    |
    | @see https://github.com/smsapi/smsapi-php-client#how-to-pick-a-service
    */
    'service' => 'com',

    /*
    |--------------------------------------------------------------------------
    | Default options
    |--------------------------------------------------------------------------
    |
    | These options will be set if user does not provide their own in code.
    |
    */
    'defaults' => [
        /*
        |----------------------------------------------------------------------
        | Common default options
        |----------------------------------------------------------------------
        |
        | Setting options here will take precedence
        | over those set in dashboard.
        |
        | Common supported options: "notify_url", "partner", "test".
        |
        */
        'common' => [
            //'notify_url' => env('SMSAPI_NOTIFY_URL'),
            //'partner' => env('SMSAPI_PARTNER'),
            'test' => env('SMSAPI_TEST', false),
        ],

        /*
        |----------------------------------------------------------------------
        | SMS default options
        |----------------------------------------------------------------------
        |
        | Setting common options here will take precedence
        | over those set in common section.
        |
        | SMS only supported options: "from", "fast", "flash", "encoding",
        | "normalize", "nounicode", "single".
        |
        */
        'sms' => [
            //'from' => env('SMSAPI_FROM'),
            //'fast' => false,
            //'flash' => false,
            //'encoding' => 'utf-8',
            //'normalize' => false,
            //'noUnicode' => false,
            //'single' => false,
        ],

        /*
        |----------------------------------------------------------------------
        | MMS default options
        |----------------------------------------------------------------------
        |
        | Setting common options here will take precedence
        | over those set in common section.
        |
        */
        'mms' => [
        ],
    ],
];
