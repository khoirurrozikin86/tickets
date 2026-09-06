<?php

return [

    'environment' => env('ESPAY_ENV', 'sandbox'),

    'base_url' => env(
        'ESPAY_BASE_URL',
        'https://sandbox-api.espay.id'
    ),

    'partner_id' => env('ESPAY_PARTNER_ID'),

    'merchant_id' => env('ESPAY_MERCHANT_ID'),

    'channel_id' => env(
        'ESPAY_CHANNEL_ID',
        'ESPAY'
    ),

    'product_code' => env(
        'ESPAY_PRODUCT_CODE',
        'QRIS'
    ),

    'private_key_path' => env(
        'ESPAY_PRIVATE_KEY_PATH'
    ),

    'timeout' => env(
        'ESPAY_TIMEOUT',
        30
    ),

];
