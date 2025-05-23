<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'yandex' => [
        'client_id' => env('YANDEX_CLIENT_ID') ?: trim(@file_get_contents(env('YANDEX_CLIENT_ID_FILE'))),
        'client_secret' => env('YANDEX_CLIENT_SECRET') ?: trim(@file_get_contents(env('YANDEX_CLIENT_SECRET_FILE'))),
        'redirect' => env('YANDEX_REDIRECT_URI'),

    ],

    'pastebin' => [
        'pastebin_api_key' => env('PASTEBIN_API_KEY') ?: trim(@file_get_contents(env('PASTEBIN_API_KEY_FILE'))),
        'pastebin_url' => env('PASTEBIN_URL'),
        'paste_user_url' => env('PASTEBIN_USER_URL'),
        'base_url_pastebin' => env('BASE_URL_PASTEBIN'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
