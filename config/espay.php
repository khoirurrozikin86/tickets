<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    */

    'environment' => env('ESPAY_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env(
        'ESPAY_BASE_URL',
        'https://sandbox-api.espay.id'
    ),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'merchant_code' => env('ESPAY_MERCHANT_CODE'),

    'api_key' => env('ESPAY_API_KEY'),

    'signature_key' => env('ESPAY_SIGNATURE_KEY'),

    'api_password' => env('ESPAY_API_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | QRIS
    |--------------------------------------------------------------------------
    */

    'product_code' => env(
        'ESPAY_PRODUCT_CODE',
        'QRIS'
    ),

    'channel_id' => env(
        'ESPAY_CHANNEL_ID',
        'ESPAY'
    ),

    'qris_endpoint' => env(
        'ESPAY_QRIS_ENDPOINT',
        '/api/v1.0/qr/qr-mpm-generate'
    ),

    /*
    |--------------------------------------------------------------------------
    | Private Key
    |--------------------------------------------------------------------------
    */

    'private_key_path' => env(
        'ESPAY_PRIVATE_KEY_PATH',
        'storage/app/private/espay/private.key'
    ),


    'public_key_path' => env(
    'ESPAY_PUBLIC_KEY_PATH',
    'storage/app/private/espay/public.key'
),



    /*
    |--------------------------------------------------------------------------
    | HTTP
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env(
        'ESPAY_TIMEOUT',
        30
    ),

];
