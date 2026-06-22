<?php

namespace App\Controllers;

use App\Core\Controller;

class KategoriController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Daftar Kategori';
        $data['kategori'] = $this->model('Kategori')->getAllKategori();

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('kategori/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Kategori';
        $data['kategori'] = $this->model('Kategori')->getKategoriById($id);

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('kategori/detail', $data);
        $this->view('templates/footer');
    }

    public function tambah()
    {
        if ($this->model('Kategori')->createKategori($_POST) > 0) {
            header('Location: ' . BASE_URL . '/kategori');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/kategori');
            exit;
        }
    }

    public function edit()
    {

    }

    public function ubah()
    {
        if ($this->model('Kategori')->updateKategori($_POST['id'], $_POST) > 0) {
            header('Location: ' . BASE_URL . '/kategori');
            exit;
        } else {
            header('Location: ' . BASE_URL . '/kategori');
            exit;
        }
    }

    public function hapus($id)
    {
        $cek = $this->model('Kategori')
            ->cekProdukKategori($id);

        if ($cek['total'] > 0) {

            echo "
        <script>
            alert('Kategori tidak dapat dihapus karena masih digunakan produk');
            window.location.href='" . BASE_URL . "/kategori';
        </script>";
            exit;
        }

        $this->model('Kategori')->deleteKategori($id);

        header('Location: ' . BASE_URL . '/kategori');
        exit;
    }
}
