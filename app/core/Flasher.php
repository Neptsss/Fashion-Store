<?php
namespace App\Core;

class Flasher
{
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe 
        ];
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            $icon = 'bi-info-circle-fill';
            if ($_SESSION['flash']['tipe'] == 'success') {
                $icon = 'bi-check-circle-fill';
            } elseif ($_SESSION['flash']['tipe'] == 'danger') {
                $icon = 'bi-exclamation-circle-fill';
            }

            echo '
            <div class="toast-flash toast-' . $_SESSION['flash']['tipe'] . '" id="flashMessage">
                <i class="bi ' . $icon . ' toast-icon"></i>
                <div class="toast-body">
                    <span class="toast-title">' . ucfirst($_SESSION['flash']['aksi']) . '</span>
                    <span class="toast-text">' . $_SESSION['flash']['pesan'] . '</span>
                </div>
                <button type="button" class="toast-close" onclick="closeToast()"><i class="bi bi-x"></i></button>
            </div>
            ';

            unset($_SESSION['flash']);
        }
    }
}
