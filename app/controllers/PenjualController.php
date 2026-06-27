<?php

namespace App\Controllers;

use App\Core\Controller;

class PenjualController extends Controller
{
    public function index()
    {
        $produkModel = $this->model('Produk');
        $kategoriModel = $this->model('Kategori');
        $data['kategori'] = $kategoriModel->getAllKategori();
        if (isset($_GET['search'])) {
            $data['produk'] = $produkModel->searchProduk($_GET['search']);
        } else {
            $data['produk'] = $produkModel->getAllProduk();
        }

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/index', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function create()
    {
        $data['judul'] = 'Tambah Produk | Stellar & Co.';
        $kategoriModel = $this->model('Kategori');
        $data['kategori'] = $kategoriModel->getAllKategori();
        $data['form_data'] = $this->getFormData();

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/create', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }

        $postData = [
            'nama' => trim($_POST['nama'] ?? ''),
            'kategori_id' => (int)($_POST['kategori_id'] ?? 0),
            'stok' => (int)($_POST['stok'] ?? 0),
            'harga' => (float)($_POST['harga'] ?? 0),
            'ukuran' => trim($_POST['ukuran'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? '')
        ];

        $errors = $this->validateProductData($postData);
        $imageCheck = $this->validateUploadedImage();

        if (!empty($_FILES['foto']['name']) && !$imageCheck['valid']) {
            $errors[] = $imageCheck['message'];
        }

        if (!empty($errors)) {
            $this->saveFormData($postData);
            \App\Core\Flasher::setFlash(implode(' ', $errors), 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products/add');
            exit;
        }

        $gambar = $this->uploadImage();
        $produkModel = $this->model('Produk');

        $dataProduk = [
            'nama' => $postData['nama'],
            'kategori_id' => $postData['kategori_id'],
            'harga' => $postData['harga'],
            'foto' => $gambar ? $gambar : null,
            'deskripsi' => $postData['deskripsi']
        ];

        $produkId = $produkModel->createProduk($dataProduk);

        if ($produkId) {
            $varianModel = $this->model('VarianProduk');

            $dataVarian = [
                'produk_id' => $produkId,
                'ukuran'    => $postData['ukuran'],
                'stok'      => $postData['stok']
            ];

            $varianModel->create($dataVarian);
            $this->clearFormData();

            \App\Core\Flasher::setFlash(
                'Produk berhasil ditambahkan!',
                'Produk',
                'success'
            );

            header('Location: ' . BASE_URL . '/dashboard/products');
        } else {
            $this->saveFormData($postData);
            \App\Core\Flasher::setFlash('Gagal menambahkan produk!', 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products/add');
        }
        exit;
    }

    public function edit($id)
    {
        $produkModel = $this->model('Produk');
        $kategoriModel = $this->model('Kategori');

        $data['judul'] = 'Edit Produk | Stellar & Co.';
        $data['produk'] = $produkModel->getProdukVarianById($id);
        $data['kategori'] = $kategoriModel->getAllKategori();

        if (!$data['produk']) {
            \App\Core\Flasher::setFlash('Produk tidak ditemukan.', 'Error', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }

        $data['form_data'] = $this->getFormData($data['produk']);

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/produk/edit', $data);
        $this->view('penjual/layout/footer');
    }

    private function saveFormData(array $data)
    {
        $_SESSION['product_form'] = $data;
    }

    private function getFormData(array $fallback = [])
    {
        return $_SESSION['product_form'] ?? $fallback;
    }

    private function clearFormData()
    {
        unset($_SESSION['product_form']);
    }

    private function validateProductData(array $data)
    {
        $errors = [];

        if (trim($data['nama'] ?? '') === '') {
            $errors[] = 'Nama produk wajib diisi.';
        }

        if (empty($data['kategori_id'])) {
            $errors[] = 'Kategori produk wajib dipilih.';
        }
        
        $kategoriModel = $this->model('Kategori');

        if (!$kategoriModel->getKategoriById($data['kategori_id'])) {
            $errors[] = 'Kategori tidak ditemukan.';
        }

        if (!is_numeric($data['stok'] ?? null) || (int)$data['stok'] < 0) {
            $errors[] = 'Stok wajib berupa angka dan tidak boleh negatif.';
        }

        if (!is_numeric($data['harga'] ?? null) || (float)$data['harga'] < 0) {
            $errors[] = 'Harga wajib berupa angka dan tidak boleh negatif.';
        }

        if (trim($data['ukuran'] ?? '') === '') {
            $errors[] = 'Ukuran produk wajib dipilih.';
        }
        if (trim($data['deskripsi']) === '') {
            $errors[] = 'Deskripsi produk wajib diisi.';
        }
        if (strlen($data['deskripsi']) > 1000) {
            $errors[] = 'Deskripsi maksimal 1000 karakter.';
        }

        return $errors;
    }

    private function validateUploadedImage()
    {
        if (!isset($_FILES['foto']) || empty($_FILES['foto']['name'])) {
            return ['valid' => true, 'message' => ''];
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'message' => 'Gambar tidak dapat diunggah.'];
        }

        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            return ['valid' => false, 'message' => 'Format gambar harus JPG, JPEG, atau PNG.'];
        }

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES['foto']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            return [
                'valid' => false,
                'message' => 'File yang diunggah bukan gambar yang valid.'
            ];
        }

        if ($ukuranFile > 2000000) {
            return ['valid' => false, 'message' => 'Ukuran gambar maksimal 2 MB.'];
        }

        return ['valid' => true, 'message' => ''];
    }

    private function uploadImage()
    {
        $namaFile = $_FILES['foto']['name'] ?? '';
        $ukuranFile = $_FILES['foto']['size'] ?? 0;
        $error = $_FILES['foto']['error'] ?? 4;
        $tmpName = $_FILES['foto']['tmp_name'] ?? '';

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

        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
        $uploadPath = __DIR__ . '/../../public/images/products/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (move_uploaded_file($tmpName, $uploadPath . $namaFileBaru)) {
            return $namaFileBaru;
        }

        return false;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/products');
            exit;
        }

        $postData = [
            'nama' => trim($_POST['nama'] ?? ''),
            'kategori_id' => (int)($_POST['kategori_id'] ?? 0),
            'stok' => (int)($_POST['stok'] ?? 0),
            'harga' => (float)($_POST['harga'] ?? 0),
            'ukuran' => trim($_POST['ukuran'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            'produk_id' => $_POST['produk_id'] ?? '',
            'varian_id' => $_POST['varian_id'] ?? ''
        ];

        $errors = $this->validateProductData($postData);
        $imageCheck = $this->validateUploadedImage();

        if (!empty($_FILES['foto']['name']) && !$imageCheck['valid']) {
            $errors[] = $imageCheck['message'];
        }

        if (!empty($errors)) {
            $this->saveFormData($postData);
            \App\Core\Flasher::setFlash(implode(' ', $errors), 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/dashboard/products/edit/' . ($postData['varian_id'] ?? ''));
            exit;
        }

        $produkModel = $this->model('Produk');
        $varianModel = $this->model('VarianProduk');

        $produkLama = $produkModel->getProdukById($_POST['produk_id']);

        if ($_FILES['foto']['error'] === 4) {
            $gambar = $produkLama['foto'];
        } else {
            $gambar = $this->uploadImage();

            if ($gambar && $produkLama['foto']) {
                $path = __DIR__ . '/../../public/images/products/' . $produkLama['foto'];

                if (file_exists($path)) {
                    unlink($path);
                }
            } elseif (!$gambar) {
                $gambar = $produkLama['foto'];
            }
        }

        $dataProduk = [
            'nama' => $postData['nama'],
            'kategori_id' => $postData['kategori_id'],
            'harga' => $postData['harga'],
            'foto' => $gambar,
            'deskripsi' => $postData['deskripsi']
        ];

        $produkModel->updateProduk($_POST['produk_id'], $dataProduk);

        $dataVarian = [
            'ukuran' => $postData['ukuran'],
            'stok' => $postData['stok']
        ];

        $varianModel->update($_POST['varian_id'], $dataVarian);
        $this->clearFormData();

        \App\Core\Flasher::setFlash(
            'Produk berhasil diperbarui!',
            'Produk',
            'success'
        );

        header('Location: ' . BASE_URL . '/dashboard/products');
        exit;
    }


    public function delete($id)
    {
        $produkModel = $this->model('Produk');
        $produk = $produkModel->getProdukById($id);

        if ($produk && $produkModel->deleteProduk($id)) {
            if ($produk['foto']) {
                $path = __DIR__ . '/../../public/images/products/' . $produk['foto'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            \App\Core\Flasher::setFlash('Produk berhasil dihapus!', 'Produk', 'Sucess');
        } else {
            \App\Core\Flasher::setFlash('Gagal menghapus produk!', 'Produk', 'danger');
        }

        header('Location: ' . BASE_URL . '/dashboard/products');
        exit;
    }
}
