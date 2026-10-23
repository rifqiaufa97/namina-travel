<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Manual Bank Transfer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for manual bank transfers including primary bank details,
    | account numbers, and instructions for customers.
    |
    */

    'bank' => [
        'name' => env('PAYMENT_BANK_NAME', 'Bank Central Asia (BCA)'),
        'code' => 'BCA',
        'account_number' => env('PAYMENT_ACCOUNT_NUMBER', '0060849510'),
        'account_name' => env('PAYMENT_ACCOUNT_NAME', 'RIFQI AUFA RACHMADI'),
        'logo' => 'images/banks/bca.png',
    ],

    'expiry_hours' => env('PAYMENT_EXPIRY_HOURS', 24),
];
