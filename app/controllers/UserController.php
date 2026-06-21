<?php
class UserController extends Controller
{
    public function profile()
    {
        $this->view('templates/header', ['judul' => 'Profile | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/profile');
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
