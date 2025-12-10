<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vonage API Key
    |--------------------------------------------------------------------------
    |
    | Your Vonage/Nexmo API key. This can be found in your Vonage dashboard.
    |
    */
    'api_key' => env('VONAGE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Vonage API Secret
    |--------------------------------------------------------------------------
    |
    | Your Vonage/Nexmo API secret. This can be found in your Vonage dashboard.
    |
    */
    'api_secret' => env('VONAGE_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Vonage Application ID
    |--------------------------------------------------------------------------
    |
    | Your Vonage application ID. This is used for advanced features like
    | inbound messages and voice calls.
    |
    */
    'application_id' => env('VONAGE_APPLICATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | Vonage Private Key
    |--------------------------------------------------------------------------
    |
    | Path to your Vonage application private key file.
    |
    */
    'private_key' => env('VONAGE_PRIVATE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Vonage SMS From
    |--------------------------------------------------------------------------
    |
    | The default sender name for SMS messages. This can be a phone number
    | or a string (max 11 characters for alphanumeric sender IDs).
    |
    */
    'sms_from' => env('VONAGE_SMS_FROM', 'Koperasi'),

    /*
    |--------------------------------------------------------------------------
    | Vonage Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | Enable sandbox mode for testing without sending actual SMS.
    |
    */
    'sandbox' => env('VONAGE_SANDBOX', false),
];