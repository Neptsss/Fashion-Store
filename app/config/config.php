<?php

$envPath = __DIR__ . '/../../.env';

if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);

    define('BASE_URL', $env['BASE_URL'] ?? 'http://localhost/project_akhir/public');
    define('DB_HOST', $env['DB_HOST'] ?? '127.0.0.1');
    define('DB_USER', $env['DB_USER'] ?? 'root');
    define('DB_PASS', $env['DB_PASS'] ?? '');
    define('DB_NAME', $env['DB_NAME'] ?? '');
} else {
    die(".env tidak ditemukan, duplikasi file .env.example dan ubah menjadi .env lalu dikonfigurasi");
}
