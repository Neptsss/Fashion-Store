<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;

class Authentication extends Controller
{
    public function register()
    {
        $this->view('templates/header');
        $this->view('Authentication/register');
    }

    public function store()
    {
        $nama_lengkap = trim($_POST['nama_lengkap']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            Flasher::setFlash('Semua field wajib diisi!', 'Registrasi gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($password !== $confirm_password) {
            Flasher::setFlash('Password dan konfirmasi password tidak cocok!', 'Registrasi gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($this->model('User')->getUserByUsername($username)) {
            Flasher::setFlash('Username sudah digunakan!', 'Registrasi gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
        
        if ($this->model('User')->getUserByEmail($email)) {
            Flasher::setFlash('Email sudah digunakan!', 'Registrasi gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        if ($this->model('User')->createUser($data)) {
            Flasher::setFlash('Registrasi berhasil, silakan login!', 'Registrasi sukses', 'success');
            header('Location: ' . BASE_URL . '/login');
            exit;
        } else {
            Flasher::setFlash('Terjadi kesalahan, silakan coba lagi!', 'Registrasi gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
    }

    public function login()
    {
        $this->view('templates/header');
        $this->view('Authentication/login');
    }

    public function authenticate()
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
        $_SESSION['nama'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'Penjual') {
            Flasher::setFlash('Berhasil masuk sebagai Penjual!', 'Login sukses', 'success');
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            Flasher::setFlash('Login berhasil!', 'Selamat datang', 'success');
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}