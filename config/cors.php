<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi CORS agar frontend (Vue/React/Next.js) dapat mengakses API.
    | Sesuaikan 'allowed_origins' dengan URL frontend Anda di production.
    |
    */

    'paths' => ['api/*', 'storage/*'],

    'allowed_methods' => ['*'],

    // Ganti dengan URL frontend Anda, misal: ['https://pkl.injourney.co.id']
    // Untuk development, bisa pakai ['*'] — JANGAN pakai '*' di production!
    'allowed_origins' => [],

'allowed_origins_patterns' => [
    '#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#',
],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
