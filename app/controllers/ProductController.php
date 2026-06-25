<?php

namespace App\Controllers;

use App\Core\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Daftar Produk | Stellar & Co.';
        $produkModel = $this->model('Produk');

        // Cek apakah ada filter kategori
        if (isset($_GET['kategori'])) {
            $data['produk'] = $produkModel->getProdukByKategori($_GET['kategori']);
        } elseif (isset($_GET['keyword'])) {
            $data['produk'] = $produkModel->searchProduk($_GET['keyword']);
        } else {
            $data['produk'] = $produkModel->getAllProduk();
        }

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/products', $data);
        $this->view('templates/footer');
    }

    /**
     * Tampil detail produk dengan varian
     */
    public function detail($id = null)
    {
        if (!$id) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        $produkModel = $this->model('Produk');
        $varianModel = $this->model('VarianProduk');

        $data['judul'] = 'Detail Produk | Stellar & Co.';
        $data['produk'] = $produkModel->getProdukWithTotalStock($id);
        
        if (!$data['produk']) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        $data['varian'] = $varianModel->getByProdukId($id);

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/detail', $data);
        $this->view('templates/footer');
    }

    /**
     * Checkout produk dengan varian
     */
    public function checkout($produk_id, $qty, $varian_id = null, $size = null)
    {
        $produkModel = $this->model('Produk');
        $varianModel = $this->model('VarianProduk');

        $data['judul'] = 'Checkout Product | Stellar & Co.';
        $data['produk'] = $produkModel->getProdukById($produk_id);

        if ($varian_id) {
            $data['varian'] = $varianModel->getById($varian_id);
            $data['ukuran'] = $data['varian']['ukuran'];
        } else {
            $data['ukuran'] = $size;
        }

        $data['qty'] = $qty;

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/checkout', $data);
        $this->view('templates/footer');
    }

    /**
     * Upload image helper
     */
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

    /**
     * Tambah produk baru
     */
    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        $gambar = $this->uploadImage();
        $produkModel = $this->model('Produk');

        $dataProduk = [
            'nama' => $_POST['nama'] ?? '',
            'kategori_id' => $_POST['kategori_id'] ?? 1,
            'harga' => $_POST['harga'] ?? 0,
            'foto' => $gambar ? $gambar : null,
            'deskripsi' => $_POST['deskripsi'] ?? ''
        ];

        $produkId = $produkModel->createProduk($dataProduk);

        if ($produkId) {
            // Jika ada data varian, tambahkan varian
            if (!empty($_POST['ukuran']) && !empty($_POST['stok'])) {
                $varianModel = $this->model('VarianProduk');
                $ukuranArray = $_POST['ukuran'];
                $stokArray = $_POST['stok'];

                foreach ($ukuranArray as $index => $ukuran) {
                    if ($ukuran && isset($stokArray[$index])) {
                        $dataVarian = [
                            'produk_id' => $produkId,
                            'ukuran' => $ukuran,
                            'stok' => (int)$stokArray[$index]
                        ];
                        $varianModel->create($dataVarian);
                    }
                }
            }

            \App\Core\Flasher::setFlash('Produk berhasil ditambahkan!','Produk', 'success');
            header('Location: ' . BASE_URL . '/products');
        } else {
            \App\Core\Flasher::setFlash('Gagal menambahkan produk!','Produk', 'danger');
            header('Location: ' . BASE_URL . '/products');
        }
        exit;
    }

    /**
     * Edit produk
     */
    public function edit($id)
    {
        $produkModel = $this->model('Produk');
        $varianModel = $this->model('VarianProduk');

        $data['produk'] = $produkModel->getProdukById($id);
        $data['varian'] = $varianModel->getByProdukId($id);
        $data['judul'] = 'Edit Produk | Stellar & Co.';

        $this->view('templates/header', $data);
        $this->view('partials/navbar');
        $this->view('products/edit', $data);
        $this->view('templates/footer');
    }

    /**
     * Update produk
     */
    public function ubah()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        $produkModel = $this->model('Produk');
        $produkLama = $produkModel->getProdukById($_POST['id']);

        // Handle image upload
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
            'nama' => $_POST['nama'] ?? '',
            'kategori_id' => $_POST['kategori_id'] ?? 1,
            'harga' => $_POST['harga'] ?? 0,
            'foto' => $gambar,
            'deskripsi' => $_POST['deskripsi'] ?? ''
        ];

        if ($produkModel->updateProduk($_POST['id'], $dataProduk)) {
            // Update varian jika ada
            if (!empty($_POST['ukuran']) && !empty($_POST['stok'])) {
                $varianModel = $this->model('VarianProduk');
                $ukuranArray = $_POST['ukuran'];
                $stokArray = $_POST['stok'];

                foreach ($ukuranArray as $index => $ukuran) {
                    if ($ukuran && isset($stokArray[$index])) {
                        $dataVarian = [
                            'ukuran' => $ukuran,
                            'stok' => (int)$stokArray[$index]
                        ];
                        // Jika ada varian_id, update; jika tidak, buat baru
                        if (isset($_POST['varian_id'][$index])) {
                            $varianModel->update($_POST['varian_id'][$index], $dataVarian);
                        } else {
                            $dataVarian['produk_id'] = $_POST['id'];
                            $varianModel->create($dataVarian);
                        }
                    }
                }
            }

            \App\Core\Flasher::setFlash('Produk berhasil diupdate!', 'Produk','success');
            header('Location: ' . BASE_URL . '/products');
        } else {
            \App\Core\Flasher::setFlash('Gagal mengupdate produk!', 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/products');
        }
        exit;
    }

   
    public function hapus($id)
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

            \App\Core\Flasher::setFlash('Produk berhasil dihapus!', 'Produk','Sucess');
        } else {
            \App\Core\Flasher::setFlash('Gagal menghapus produk!', 'Produk', 'danger');
        }

        header('Location: ' . BASE_URL . '/products');
        exit;
    }

    /**
     * Hapus varian produk
     */
    public function hapusVarian($varian_id)
    {
        $varianModel = $this->model('VarianProduk');
        $varian = $varianModel->getById($varian_id);

        if ($varian && $varianModel->delete($varian_id)) {
            \App\Core\Flasher::setFlash('Varian produk berhasil dihapus!','Produk', 'success');
        } else {
            \App\Core\Flasher::setFlash('Gagal menghapus varian!','Produk', 'danger');
        }

        header('Location: ' . BASE_URL . '/products');
        exit;
    }
}
