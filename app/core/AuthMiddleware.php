<?php

namespace App\Core;

class AuthMiddleware
{
    public static function isPenjual()
    {
        if (!isset($_SESSION['login'])) {
            Flasher::setFlash('Silakan login terlebih dahulu!', 'Akses ditolak', 'warning');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SESSION['role'] !== 'Penjual') {
            Flasher::setFlash('Anda tidak memiliki akses ke halaman ini!', 'Akses ditolak', 'danger');
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}
