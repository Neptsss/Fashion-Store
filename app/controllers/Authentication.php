<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;

class Authentication extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('user');
    }
    public function index()
    {
        $this->view('templates/header', ['judul' => "Login | Stellar & Co."]);
        $this->view('authentication/login');
        $this->view('templates/footer');
    }

    public function login()
    {

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            Flasher::setFlash('Username dan Password wajib diisi.', 'Login Gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }


        $user = $this->userModel->getUserByUsername($username);

        if ($user) {
            if (password_verify($password, $user['password'])) {

                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email'] = $user['email'];

                Flasher::setFlash('Selamat datang kembali, ' . $user['nama_lengkap'] . '!', 'Login Berhasil', 'success');
                header('Location: ' . BASE_URL . '/');
                exit;
            } else {
                Flasher::setFlash('Password yang Anda masukkan salah.', 'Login Gagal', 'danger');
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        } else {
            Flasher::setFlash('Username tidak terdaftar.', 'Login Gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function register()
    {
        $this->view('templates/header', ['judul' => "Regsiter | Stellar & Co."]);
        $this->view('authentication/register');
        $this->view('templates/footer');
    }

    public function userRegister()
    {
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            Flasher::setFlash('Semua kolom wajib diisi.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            Flasher::setFlash('Username hanya boleh berisi huruf dan angka.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($this->userModel->getUserByUsername($username)) {
            Flasher::setFlash('Username sudah terpakai, silakan gunakan yang lain.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($this->userModel->getUserByEmail($email)) {
            Flasher::setFlash('Email sudah terdaftar.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (strlen($password) < 8) {
            Flasher::setFlash('Password minimal harus 8 karakter.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($password !== $confirm_password) {
            Flasher::setFlash('Password dan konfirmasi password tidak cocok.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $this->userModel->createUser($data);

        Flasher::setFlash('Registrasi berhasil! Silakan login.', 'Register Berhasil', 'success');
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

   public function logout()
    {
        $_SESSION = [];

        session_unset();

        session_destroy();

        session_start();

        Flasher::setFlash('Anda telah berhasil keluar.', 'Logout Berhasil', 'success');

        header('Location: ' . BASE_URL . '');
        exit;
    }
}
