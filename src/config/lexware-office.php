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
        | Base URL and timeout settings for the Lexware Office API
        |
        */
        'base_url' => env('LEXWARE_OFFICE_API_URL', 'https://api.lexware.io/v1/'),
        'timeout' => [
            'connect' => env('LEXWARE_OFFICE_CONNECT_TIMEOUT', 30),
            'request' => env('LEXWARE_OFFICE_REQUEST_TIMEOUT', 30),
        ],
        'retry' => [
            'attempts' => env('LEXWARE_OFFICE_RETRY_ATTEMPTS', 3),
            'delay' => env('LEXWARE_OFFICE_RETRY_DELAY', 1000), // milliseconds
        ],
    ],
    
    'rate_limit' => [
        /*
        |--------------------------------------------------------------------------
        | Rate Limiting Configuration
        |--------------------------------------------------------------------------
        |
        | Configure rate limiting to comply with API limits
        |
        */
        'store' => env('LEXWARE_OFFICE_RATE_LIMIT_STORE', 'file'),
        'requests_per_second' => env('LEXWARE_OFFICE_RATE_LIMIT_RPS', 2),
        'burst_limit' => env('LEXWARE_OFFICE_BURST_LIMIT', 10),
    ],
    
    'pagination' => [
        /*
        |--------------------------------------------------------------------------
        | Pagination Settings
        |--------------------------------------------------------------------------
        |
        | Default pagination settings for API requests
        |
        */
        'default_size' => env('LEXWARE_OFFICE_PAGE_SIZE', 25),
        'max_size' => env('LEXWARE_OFFICE_MAX_PAGE_SIZE', 250),
    ],
    
    'cache' => [
        /*
        |--------------------------------------------------------------------------
        | Response Caching
        |--------------------------------------------------------------------------
        |
        | Cache API responses to reduce API calls and improve performance
        |
        */
        'enabled' => env('LEXWARE_OFFICE_CACHE_ENABLED', false),
        'ttl' => env('LEXWARE_OFFICE_CACHE_TTL', 300), // 5 minutes
        'store' => env('LEXWARE_OFFICE_CACHE_STORE', 'file'),
        'prefix' => env('LEXWARE_OFFICE_CACHE_PREFIX', 'lexware_office_api'),
    ],
    
    'logging' => [
        /*
        |--------------------------------------------------------------------------
        | API Logging
        |--------------------------------------------------------------------------
        |
        | Configure logging for API requests and responses
        |
        */
        'enabled' => env('LEXWARE_OFFICE_LOGGING_ENABLED', false),
        'channel' => env('LEXWARE_OFFICE_LOG_CHANNEL', 'default'),
        'level' => env('LEXWARE_OFFICE_LOG_LEVEL', 'info'),
        'include_headers' => env('LEXWARE_OFFICE_LOG_HEADERS', false),
        'include_body' => env('LEXWARE_OFFICE_LOG_BODY', false),
    ],
];
