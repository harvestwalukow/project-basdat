<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans Payment Gateway
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'G123456789'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-DUMMY_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-DUMMY_KEY'),
    
    // Set to true for production, false for sandbox/development
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    // Set to true to enable input sanitization
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    
    // Set to true to enable 3D Secure
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];

