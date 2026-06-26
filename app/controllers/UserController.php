<?php

namespace App\Controllers;

use App\Core\Controller;
class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('user');
    }
    public function index($username = null)
    {
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'Penjual') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $this->view('templates/header', [
            'judul' => 'Profile | ' . $user['nama_lengkap'] 
        ]);
        
        $this->view('partials/navbar');

        $this->view('pembeli/profile', [
            'user' => $user
        ]);

        $this->view('templates/footer');
    }

    public function history(){
        $this->view('templates/header', ['judul' => 'History | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/history');
        $this->view('templates/footer');
    }

    public function detail_transaksi(){
        $this->view('templates/header', ['judul' => 'Transaksi Detail | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/detail_transaksi');
        $this->view('templates/footer');
    }
}
