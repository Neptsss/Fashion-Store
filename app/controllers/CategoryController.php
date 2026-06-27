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
        if (isset($_GET['search'])) {
            $data['kategori'] = $this->model('Kategori')->searchKategori($_GET['search']);
        } else {
            $data['kategori'] = $this->model('Kategori')->getAllKategori();
        }

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

    private function validateCategory(array $data, $id = null)
    {
        $errors = [];

        if (trim($data['nama'] ?? '') === '') {
            $errors[] = 'Nama kategori wajib diisi.';
        }

        if (strlen($data['nama']) > 50) {
            $errors[] = 'Nama kategori maksimal 50 karakter.';
        }

        $kategoriModel = $this->model('Kategori');

        if ($id === null) {
            if ($kategoriModel->kategoriExists($data['nama'])) {
                $errors[] = 'Nama kategori sudah digunakan.';
            }
        } else {
            if ($kategoriModel->kategoriExistsExceptId($data['nama'], $id)) {
                $errors[] = 'Nama kategori sudah digunakan.';
            }
        }


        return $errors;
    }

    public function store()
    {
        $data = [
            'nama' => trim($_POST['nama'] ?? '')
        ];

        $errors = $this->validateCategory($data);

        if (!empty($errors)) {
            Flasher::setFlash(
                implode(' ', $errors),
                'Kategori',
                'danger'
            );

            header('Location: ' . BASE_URL . '/dashboard/categories/add');
            exit;
        } else {
            $this->model('Kategori')->createKategori($data);
            Flasher::setFlash('Category successfully', 'created', 'success');
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
        $data = [
            'nama' => trim($_POST['nama'] ?? '')
        ];

        $id = $_POST['id'];

        $errors = $this->validateCategory($data, $id);

        if (!empty($errors)) {
            Flasher::setFlash(
                implode(' ', $errors),
                'Kategori',
                'danger'
            );

            header('Location: ' . BASE_URL . '/dashboard/categories/edit/' . $id);
            exit;
        } else {
            $this->model('Kategori')->updateKategori($id, $data);
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
