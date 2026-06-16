<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp OTP Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for sending OTP messages via Meta WhatsApp Cloud API
    | (Graph API). Used during user registration and profile verification.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable WhatsApp OTP
    |--------------------------------------------------------------------------
    |
    | Set to true to use WhatsApp for sending OTP instead of SMS.
    | When false, falls back to SMS (sendSms function).
    |
    */
    'use_whatsapp_otp' => env('USE_WHATSAPP_OTP', false),

    /*
    |--------------------------------------------------------------------------
    | Meta WhatsApp Cloud API (Graph API)
    |--------------------------------------------------------------------------
    |
    | Base URL for the Meta Graph API. Default is v18.0, but you can use
    | any version your app supports (e.g., v19.0, v20.0).
    |
    */
    'graph_url' => rtrim(env('WHATSAPP_GRAPH_URL', 'https://graph.facebook.com/v18.0'), '/'),

    /*
    |--------------------------------------------------------------------------
    | Phone Number ID
    |--------------------------------------------------------------------------
    |
    | Your WhatsApp Business Phone Number ID from Meta Business Suite.
    | Find it at: Meta Business Suite > WhatsApp > API Setup > From
    | This is a numeric ID, not your actual phone number.
    |
    */
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Access Token
    |--------------------------------------------------------------------------
    |
    | Your Meta app access token with whatsapp_business_messaging permission.
    | Generate from: Meta App Dashboard > Tools > Graph API Explorer
    | or via System User token in Business Settings.
    |
    | SECURITY: Never commit this token to version control. Keep it in .env only.
    |
    */
    'api_key' => env('WHATSAPP_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Country Code
    |--------------------------------------------------------------------------
    |
    | Country code for mobile numbers (without + sign).
    | Examples: 91 (India), 1 (USA), 44 (UK)
    | This is prepended to the 10-digit mobile number stored in database.
    |
    */
    'country_code' => env('WHATSAPP_OTP_COUNTRY_CODE', '91'),

    /*
    |--------------------------------------------------------------------------
    | OTP Template Name
    |--------------------------------------------------------------------------
    |
    | The name of your approved WhatsApp message template in Meta.
    | Template must be approved in Meta Business Suite before use.
    | Example: 'peak_otp', 'otp_verification', etc.
    |
    */
    'otp_template_name' => env('WHATSAPP_OTP_TEMPLATE_NAME', 'peak_otp'),

];
