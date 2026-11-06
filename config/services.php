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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'swipe' => [
        'base_url' => env('SWIPE_GST_API_URL', 'https://app.getswipe.in/api/partner/v2/utils/gstin'),
        'token' => env('SWIPE_GST_API_TOKEN', ''),
    ],

    'bhashini' => [
        'user_id' => env('BHASHINI_USER_ID', ''),
        'api_key' => env('BHASHINI_API_KEY', ''),
        'inference_api_key' => env('BHASHINI_INFERENCE_API_KEY', env('BHASHINI_API_KEY', '')),
        'pipeline_id' => env('BHASHINI_PIPELINE_ID', ''),
        'ulca_base_url' => env('BHASHINI_ULCA_URL', 'https://meity-auth.ulcacontrib.org/ulca/apis/v0/model/getModelsPipeline'),
        'inference_base_url' => env('BHASHINI_INFERENCE_URL', 'https://dhruva-api.bhashini.gov.in/services/inference/pipeline'),
    ],

];
