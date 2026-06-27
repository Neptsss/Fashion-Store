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

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['telp'] = $user['telp'] ?? null;
                $_SESSION['alamat'] = $user['alamat'] ?? null;
                $_SESSION['foto'] = $user['foto'] ?? null;

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
        $this->view('templates/header', ['judul' => "Register | Stellar & Co."]);
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

        if ($this->userModel->getUserByEmail($email)) {
            Flasher::setFlash('Email sudah terdaftar.', 'Register Gagal', 'danger');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flasher::setFlash('Format email tidak valid.', 'Register Gagal', 'danger');
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

   

    public function authenticate()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            Flasher::setFlash('Username dan password wajib diisi!', 'Login gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user = $this->model('User')->getUserByUsername($username);

        if (!$user) {
            Flasher::setFlash('Username tidak ditemukan!', 'Login gagal', 'danger');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

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