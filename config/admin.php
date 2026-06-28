<?php

return [
    // Kredensial admin dibaca dari .env, BUKAN hardcode di controller/JS.
    // Ganti ADMIN_USERNAME & ADMIN_PASSWORD di .env untuk produksi.
    'username' => env('ADMIN_USERNAME', 'admin'),
    'password' => env('ADMIN_PASSWORD', 'admin123'),
];
