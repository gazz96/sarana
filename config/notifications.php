<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the notification system including email settings,
    | SMS settings, and in-app notification preferences.
    |
    */

    'email' => [
        'enabled' => env('NOTIFICATION_EMAIL_ENABLED', true),
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@sarpras.local'),
        'from_name' => env('MAIL_FROM_NAME', 'SARPRAS System'),
        
        // Email templates
        'templates' => [
            'workflow' => 'emails.workflow-notification',
            'reminder' => 'emails.reminder',
            'alert' => 'emails.alert',
        ],
    ],

    'sms' => [
        'enabled' => env('NOTIFICATION_SMS_ENABLED', false),
        'provider' => env('SMS_PROVIDER', 'twilio'), // twilio, nexmo, etc
        
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
    ],

    'in_app' => [
        'enabled' => true,
        'retention_days' => env('NOTIFICATION_RETENTION_DAYS', 30),
        'max_per_user' => env('NOTIFICATION_MAX_PER_USER', 100),
    ],

    'workflow_events' => [
        'problem_created' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_submitted' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_accepted' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_finished' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_cancelled' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_approved_management' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_approved_admin' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
        'problem_approved_finance' => [
            'email' => true,
            'sms' => false,
            'in_app' => true,
        ],
    ],
];