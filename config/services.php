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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
        'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'facebook' => [
        'client_id' => '945800103975894',
        'client_secret' => 'c4f5186f2b2e2216d585bef79cda1f97',
        'redirect' => 'https://myevnt.ai/auth/facebook/callback',
    ],

    'paymob' => [
        'api_key' => env('PAYMOB_API_KEY'),
        'iframe_id' => env('PAYMOB_IFRAME_ID', '860671'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
        'currency' => env('PAYMOB_CURRENCY', 'EGP'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'), // e.g. whatsapp:+14155238886 (Sandbox) or WhatsApp Business sender
        'whatsapp_sandbox_join' => env('TWILIO_WHATSAPP_SANDBOX_JOIN', ''),
        'sms_from' => env('TWILIO_SMS_FROM'), // Your Twilio number for SMS, e.g. +14244010389
        'otp_channel' => env('TWILIO_OTP_CHANNEL', 'sms'), // 'sms' or 'whatsapp'
    ],

    'sms_misr' => [
        'sender_token' => env('SMS_MISR_SENDER_TOKEN'),
        'api_url' => env('SMS_MISR_API_URL', 'https://smsmisr.com/api/webapi'),
    ],

    'instapay' => [
        'account_name'      => env('INSTAPAY_ACCOUNT_NAME', 'My Store'),
        'mobile_number'     => env('INSTAPAY_MOBILE_NUMBER', '01122907742'),
        'ipa'               => env('INSTAPAY_ADDRESS'),
        // Optional: direct payment link generated from InstaPay app
        'payment_url'       => env('INSTAPAY_PAYMENT_URL'),
    ],

];
