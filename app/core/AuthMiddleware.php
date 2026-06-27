<?php

namespace App\Core;

class AuthMiddleware
{
    public static function auth()
    {
        if (!isset($_SESSION['user_id'])) {
            Flasher::setFlash('Silakan login terlebih dahulu!', 'Akses ditolak', 'warning');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    public static function isPembeli()
    {
        self::auth();

        if ($_SESSION['role'] !== 'Pembeli') {
            Flasher::setFlash('Anda tidak memiliki akses ke halaman ini!', 'Akses ditolak', 'danger');
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public static function isPenjual()
    {
       self::auth();

        if ($_SESSION['role'] !== 'Penjual') {
            Flasher::setFlash('Anda tidak memiliki akses ke halaman ini!', 'Akses ditolak', 'danger');
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}
