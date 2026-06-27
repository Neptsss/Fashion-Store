<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller
{
    public function index()
    {
        $data['judul'] = 'Daftar Produk | Stellar & Co.';
        $produkModel = $this->model('Produk');
        $kategoriModel = $this->model('Kategori');

        $data['kategori'] = $kategoriModel->getAllKategori();

        if (isset($_GET['kategori'])) {
            $data['produk'] = $produkModel->getProdukByKategori($_GET['kategori']);
        } elseif (isset($_GET['keyword'])) {
            $data['produk'] = $produkModel->searchProduk($_GET['keyword']);
        } else {
            $data['produk'] = $produkModel->getAllProduk();
        }

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('landing', $data);
        $this->view('templates/footer');
    }
}
