<?php

namespace App\Controllers;

use App\Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Daftar Produk | Stellar & Co.';
        if (isset($_GET['keyword'])) {
            $data['produk'] = $this->model('Produk')->searchProduk($_GET['keyword']);
        } else {
            $data['produk'] = $this->model('Produk')->getAllProduk();
        }

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/products', $data);
        $this->view('templates/footer');
    }

    public function detail($id = null)
    {
        $data['judul'] = 'Detail Produk | Stellar & Co.';
        if ($id) {
            $data['produk'] = $this->model('Produk')->getProdukById($id);
        }

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/detail', $data);
        $this->view('templates/footer');
    }

    public function checkout()
    {
        $this->view('templates/header', ['judul' => "Checkout Product | Stellar & Co."]);
        $this->view('partials/navbar');
        $this->view('products/checkout');
        $this->view('templates/footer');
    }

    private function uploadImage()
    {
        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];

        if ($error === 4) {
            return false;
        }

        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            return false;
        }

        if ($ukuranFile > 2000000) {
            return false;
        }

        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiGambar;

        move_uploaded_file($tmpName, __DIR__ . '/../../public/images/' . $namaFileBaru);

        return $namaFileBaru;
    }

    public function tambah()
    {
        $gambar = $this->uploadImage();
        if (!$gambar) {
            $_POST['foto'] = '';
        } else {
            $_POST['foto'] = $gambar;
        }

        if ($this->model('Produk')->createProduk($_POST) > 0) {
            header('Location: ' . BASE_URL . '/product');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/product');
            exit;
        }
    }

    public function edit($id)
    {
        $data['produk'] = $this->model('Produk')->getProdukById($id);
        $data['judul'] = 'Edit Produk | Stellar & Co.';
        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/edit', $data);
        $this->view('templates/footer');
    }

    public function ubah()
    {
        $produkLama = $this->model('Produk')->getProdukById($_POST['id']);
        $fotoLama = $produkLama['foto'];

        if ($_FILES['foto']['error'] === 4) {
            $gambar = $fotoLama;
        } else {
            $gambar = $this->uploadImage();
            $path = __DIR__ . '/../../public/images/' . $fotoLama;
            if ($gambar) {
                if ($fotoLama != '' && file_exists($path)) {
                    unlink($path);
                }
            } else {
                $gambar = $fotoLama;
            }
        }

        $_POST['foto'] = $gambar;

        if ($this->model('Produk')->updateProduk($_POST['id'], $_POST) > 0) {
            header('Location: ' . BASE_URL . '/product');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/product');
            exit;
        }
    }

    public function hapus($id)
    {
        $produk = $this->model('Produk')->getProdukById($id);
        $path = __DIR__ . '/../../public/images/' . $produk['foto'];
        if ($this->model('Produk')->deleteProduk($id) > 0) {
            if ($produk['foto'] != '' && file_exists($path)) {
                unlink($path);
            }

            header('Location: ' . BASE_URL . '/product');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/product');
            exit;
        }
    }
}