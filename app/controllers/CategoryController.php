<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;
use App\Core\AuthMiddleware;

class CategoryController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::isPenjual();
    }

    public function index()
    {
        $data['judul'] = 'Categories | Luxe Academy';
        $data['kategori'] = $this->model('Kategori')->getAllKategori();

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/kategori/index', $data);
        $this->view('penjual/layout/footer', $data);
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

    public function store()
    {
        if ($this->model('Kategori')->createKategori($_POST) > 0) {
            Flasher::setFlash('Category successfully', 'created', 'success');
            header('Location: ' . BASE_URL . '/dashboard/categories');
            exit;
        } else {
            Flasher::setFlash('Failed to create', 'category', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/categories');
            exit;
        }
    }

    public function create()
    {
        $data['judul'] = 'Add New Category | Luxe Academy';
        
        $this->view('penjual/layout/header', $data);
        $this->view('penjual/kategori/create', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function edit($id)
    {
        $data['judul'] = 'Edit Category | Luxe Academy';
        $data['kategori'] = $this->model('Kategori')->getKategoriById($id);
        
        $this->view('penjual/layout/header', $data);
        $this->view('penjual/kategori/edit', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function update()
    {
        if ($this->model('Kategori')->updateKategori($_POST['id'], $_POST) > 0) {
            Flasher::setFlash('Category successfully', 'updated', 'success');
            header('Location: ' . BASE_URL . '/dashboard/categories');
            exit;
        } else {
            Flasher::setFlash('Failed to update', 'category', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/categories');
            exit;
        }
    }

    public function delete($id)
    {
        $cek = $this->model('Kategori')
            ->cekProdukKategori($id);

        if ($cek['total'] > 0) {
            Flasher::setFlash('cannot be deleted because it is still used by products', 'Category', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/categories');
            exit;
        }

        $this->model('Kategori')->deleteKategori($id);
        Flasher::setFlash('Category successfully', 'deleted', 'success');
        header('Location: ' . BASE_URL . '/dashboard/categories');
        exit;
    }
}
