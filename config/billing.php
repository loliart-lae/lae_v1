<?php

return [
    'mid' => 3561,
    'key' => 'z2v6hb9e7saymcbp1lfi0fc1wd9c',
    'api_url' => 'https://www.3mpay.com/createOrder',
    'notify' => env('APP_URL') . '/billing/notify',
    'return' => env('APP_URL') . '/billing/return',
    'exchange_rate' => 100
];
