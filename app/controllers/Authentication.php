<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;

class Authentication extends Controller
{
    public function index()
    {
        $this->view('templates/header');
        $this->view('Authentication/login');
    }

    public function login()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Validasi input
        if (empty($username) || empty($password)) {
            Flasher::setFlash('Username dan password wajib diisi!', 'Login gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Ambil data user dari database
        $user = $this->model('User')->getUserByUsername($username);

        // Cek user ditemukan atau tidak
        if (!$user) {
            Flasher::setFlash('Username tidak ditemukan!', 'Login gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            Flasher::setFlash('Password salah!', 'Login gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Simpan session
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];

        Flasher::setFlash('Login berhasil!', 'Login berhasil', 'success');

        header('Location: ' . BASE_URL);
        exit;
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}