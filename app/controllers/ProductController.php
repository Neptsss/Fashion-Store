<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;
use App\Core\AuthMiddleware;

class ProductController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::isPenjual();
    }

    public function index()
    {
        $data['judul'] = 'Product Inventory | Stellar & Co';
        if (isset($_GET['keyword'])) {
            $data['produk'] = $this->model('Produk')->searchProduk($_GET['keyword']);
        } else {
            $data['produk'] = $this->model('Produk')->getAllProduk();
        }
        $data['kategori'] = $this->model('Kategori')->getAllKategori();

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/index', $data);
        $this->view('penjual/layout/footer', $data);
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

    public function checkout($id, $qty)
    {
        $produk = $this->model('Produk')->getProdukById($id);

        $this->view('templates/header', ['judul' => "Checkout Product | Stellar & Co."]);
        $this->view('partials/navbar');
        $this->view('products/checkout', ["produk" => $produk, "qty" => $qty]);
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

    public function store()
    {
        $gambar = $this->uploadImage();
        if (!$gambar) {
            $_POST['foto'] = '';
        } else {
            $_POST['foto'] = $gambar;
        }

        if ($this->model('Produk')->createProduk($_POST) > 0) {
            Flasher::setFlash('Product successfully', 'created', 'success');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        } else {
            Flasher::setFlash('Failed to create', 'product', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }
    }

    public function create()
    {
        $data['judul'] = 'Create New Product | Luxe Academy';
        $data['kategori'] = $this->model('Kategori')->getAllKategori();

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/create', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function edit($id)
    {
        $data['judul'] = 'Edit Product | Luxe Academy';
        $data['kategori'] = $this->model('Kategori')->getAllKategori();
        $data['produk'] = $this->model('Produk')->getProdukById($id);

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/edit', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function update()
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
            Flasher::setFlash('Product successfully', 'updated', 'success');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        } else {
            Flasher::setFlash('Failed to update', 'product', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }
    }

    public function delete($id)
    {
        $produk = $this->model('Produk')->getProdukById($id);
        $path = __DIR__ . '/../../public/images/' . $produk['foto'];
        if ($this->model('Produk')->deleteProduk($id) > 0) {
            if ($produk['foto'] != '' && file_exists($path)) {
                unlink($path);
            }

            Flasher::setFlash('Product successfully', 'deleted', 'success');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        } else {
            Flasher::setFlash('Failed to delete', 'product', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }
    }
}
