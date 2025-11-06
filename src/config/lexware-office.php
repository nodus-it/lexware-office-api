<?php

return [
    'auth' => [
        /*
            |--------------------------------------------------------------------------
            | Lexware office token
            |--------------------------------------------------------------------------
            |
            | API token generated in Lexware office
            |
            | https://app.lexoffice.de/addons/public-api
            |
            */
        'token' => env('LEXWARE_OFFICE_API_TOKEN', null),
    ],

    'api' => [
        /*
        |--------------------------------------------------------------------------
        | API Configuration
        |--------------------------------------------------------------------------
        |
        | Timeout settings for the Lexware Office API
        |
        */
        'timeout' => [
            'connect' => env('LEXWARE_OFFICE_CONNECT_TIMEOUT', 30),
            'request' => env('LEXWARE_OFFICE_REQUEST_TIMEOUT', 30),
        ],
    ],

    'rate_limit' => [
        /*
        |--------------------------------------------------------------------------
        | Rate Limiting Configuration
        |--------------------------------------------------------------------------
        |
        | Configure the cache store used by the rate limiter
        |
        */
        'store' => env('LEXWARE_OFFICE_RATE_LIMIT_STORE', 'file'),
    ],
];
