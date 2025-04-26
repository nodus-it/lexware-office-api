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
    'rate_limit' => [
        /*
        |--------------------------------------------------------------------------
        | Cache Store for rate limit
        |--------------------------------------------------------------------------
        |
        | For rate limit this package uses the laravel cache store. Use one of your cache store names
        |
        */
        'store' => 'file',
    ]
];
