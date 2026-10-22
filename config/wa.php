<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp (GoWa) Gateway
    |--------------------------------------------------------------------------
    | Konfigurasi koneksi ke server GoWa. Semua endpoint disimpan relatif
    | terhadap base_url agar mudah dipindah/di-maintain lewat .env.
    */

    'enabled' => (bool) env('WA_ENABLED', false),

    // Base URL server GoWa (tanpa path endpoint).
    'base_url' => rtrim(env('WA_API_URL', 'http://127.0.0.1:8810'), '/'),

    // Autentikasi HTTP Basic.
    'username' => env('WA_USERNAME'),
    'password' => env('WA_PASSWORD'),

    // Path endpoint (relatif terhadap base_url).
    'endpoints' => [
        'message' => env('WA_MESSAGE_ENDPOINT', '/send/message'),
        'document' => env('WA_DOCUMENT_ENDPOINT', '/send/file'),
    ],

    // Jumlah percobaan ulang maximum untuk satu pengiriman.
    'retries' => (int) env('WA_RETRIES', 5),

    // Nama brand yang tampil di pesan & invoice.
    'brand' => env('WA_BRAND', 'wiji.net'),

    // false = kirim pesan teks saja (uji koneksi).
    // true  = kirim pesan + lampiran PDF invoice.
    'send_document' => (bool) env('WA_SEND_DOCUMENT', false),

    // Timeout HTTP (detik).
    'timeout' => (int) env('WA_TIMEOUT', 90),
];
