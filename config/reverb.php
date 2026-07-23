<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Reverb Server
    |--------------------------------------------------------------------------
    */
    'default' => env('REVERB_SERVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    */
    'servers' => [

        'reverb' => [
            // Di mana proses "php artisan reverb:start" listening
            'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
            'port' => env('REVERB_SERVER_PORT', 8080),
            'path' => env('REVERB_SERVER_PATH', ''),
            // Hostname yang dipakai dalam info identitas server
            'hostname' => env('REVERB_HOST'),

            // Untuk HTTP (ws), kosongkan TLS
            'options' => [
                'tls' => [], // kalau nanti pakai HTTPS (wss) baru diisi cert, key, ca, dll.
            ],

            'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10_000),

            // Scaling (opsional) - biarkan false kalau single node
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
                'server' => [
                    'url'      => env('REDIS_URL'),
                    'host'     => env('REDIS_HOST', '127.0.0.1'),
                    'port'     => env('REDIS_PORT', '6379'),
                    'username' => env('REDIS_USERNAME'),
                    'password' => env('REDIS_PASSWORD'),
                    'database' => env('REDIS_DB', '0'),
                    'timeout'  => env('REDIS_TIMEOUT', 60),
                ],
            ],

            'pulse_ingest_interval'     => env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    */
    'apps' => [

        // kita pakai provider config dari .env
        'provider' => 'config',

        'apps' => [
            [
                'key'     => env('REVERB_APP_KEY'),
                'secret'  => env('REVERB_APP_SECRET'),
                'app_id'  => env('REVERB_APP_ID'),

                // <-- PENTING: default ke http + port 8080, bukan https:443
                'options' => [
                    'host'   => env('REVERB_HOST'),
                    'port'   => env('REVERB_PORT', 8080),
                    'scheme' => env('REVERB_SCHEME', 'http'),
                    'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
                ],

                // Untuk tes boleh '*'; production idealnya batasi ke domain kamu
                'allowed_origins' => ['*'],

                'ping_interval'     => env('REVERB_APP_PING_INTERVAL', 60),
                'activity_timeout'  => env('REVERB_APP_ACTIVITY_TIMEOUT', 30),
                'max_message_size'  => env('REVERB_APP_MAX_MESSAGE_SIZE', 10_000),
            ],
        ],

    ],

];
