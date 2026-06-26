<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller
{
    private $produkModel;
    public function __construct()
    {
        $this->produkModel = $this->model('produk');
    }
    public function index($id = null)
    {
        $produk = $this->produkModel->getProdukLimit(6);

        $this->view('templates/header', [
            'judul' => "Home | Stellar & Co"
        ]);
        $this->view('partials/navbar');
        $this->view('landing', ['produk' => $produk]);
        $this->view('templates/footer');
    }
}
