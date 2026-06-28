<?php

namespace App\Controllers;

use App\Core\Controller;

class ProductController extends Controller
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
     * Checkout produk dengan varian atau multi-item
     */
    public function checkout($produk_id = null, $qty = null, $varian_id = null, $size = null)
    {
        $produkModel = $this->model('Produk');
        $data['judul'] = 'Checkout Product | Stellar & Co.';

        $productVariants = $produkModel->getAllProduk();
        foreach ($productVariants as &$item) {
            if (!isset($item['produk_id']) && isset($item['id'])) {
                $item['produk_id'] = $item['id'];
            }
            if (empty($item['foto'])) {
                $item['foto'] = 'https://picsum.photos/100/100?random=' . ($item['produk_id'] ?? rand(1, 999));
            }
        }
        unset($item);
        $data['productVariants'] = $productVariants;
        $data['prefillItem'] = null;

        $queryProductId = $produk_id ?? ($_GET['product_id'] ?? null);
        $queryVarianId = $varian_id ?? ($_GET['varian_id'] ?? null);
        $queryQty = $qty ?? ($_GET['qty'] ?? 1);

        if ($queryVarianId) {
            $variant = $produkModel->getProdukVarianById($queryVarianId);
            if ($variant) {
                $data['prefillItem'] = [
                    'produk_id' => $variant['produk_id'] ?? $queryProductId,
                    'varian_id' => $variant['varian_id'],
                    'nama' => $variant['nama'],
                    'foto' => $variant['foto'] ?: 'https://picsum.photos/100/100',
                    'ukuran' => $variant['ukuran'],
                    'stok' => $variant['stok'],
                    'harga' => $variant['harga'],
                    'quantity' => max(1, min((int)$queryQty, $variant['stok']))
                ];
            }
        }

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

    /**
     * Proses checkout - Simpan pesanan ke database
     */
    public function prosesCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            \App\Core\Flasher::setFlash('Silakan login terlebih dahulu!', 'Produk', 'warning');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $produkModel = $this->model('Produk');
        $varianModel = $this->model('VarianProduk');
        $pesananModel = $this->model('Pesanan');
        $detailPesananModel = $this->model('Detail_Pesanan');

        $productIds = $_POST['product_id'] ?? [];
        $varianIds = $_POST['varian_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $bukti_bayar = $_FILES['bukti_bayar'] ?? null;

        if (!is_array($productIds) || !is_array($varianIds) || !is_array($quantities)) {
            \App\Core\Flasher::setFlash('Data pesanan tidak valid!', 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }

        $items = [];
        foreach ($varianIds as $index => $varian_id) {
            $varian_id = $varian_id ?? null;
            $quantity = (int)($quantities[$index] ?? 0);

            if (!$varian_id || $quantity < 1) {
                continue;
            }

            $variant = $varianModel->getWithProduk($varian_id);
            if (!$variant) {
                \App\Core\Flasher::setFlash('Varian produk tidak ditemukan!', 'Produk', 'danger');
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }

            if ($variant['stok'] < $quantity) {
                \App\Core\Flasher::setFlash('Stok varian ' . htmlspecialchars($variant['ukuran']) . ' tidak mencukupi!', 'Produk', 'danger');
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }

            $harga = (float)$variant['harga'];
            $sub_total = $harga * $quantity;

            $items[] = [
                'produk_id' => $variant['produk_id'],
                'varian_id' => $varian_id,
                'quantity' => $quantity,
                'harga' => $harga,
                'sub_total' => $sub_total
            ];
        }

        if (empty($items)) {
            \App\Core\Flasher::setFlash('Silakan pilih minimal satu produk.', 'Checkout', 'danger');
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }

        $bukti_filename = null;
        if ($bukti_bayar && $bukti_bayar['error'] === 0) {
            $bukti_filename = $this->uploadPaymentProof($bukti_bayar);
            if (!$bukti_filename) {
                \App\Core\Flasher::setFlash('Format bukti pembayaran tidak valid!', 'Produk', 'danger');
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }
        }

        $subTotal = array_sum(array_column($items, 'sub_total'));
        $ongkir = 15000;
        $total_harga = $subTotal + $ongkir;

        $dataPesanan = [
            'user_id' => $_SESSION['user_id'],
            'total_harga' => $total_harga,
            'bukti_pembayaran' => $bukti_filename,
            'status' => 'Diproses'
        ];

        $pesanan_id = $pesananModel->create($dataPesanan);

        if (!$pesanan_id) {
            \App\Core\Flasher::setFlash('Gagal membuat pesanan!', 'Produk', 'danger');
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }

        $createdDetails = false;
        foreach ($items as $item) {
            $dataDetailPesanan = [
                'pesanan_id' => $pesanan_id,
                'varian_id' => $item['varian_id'],
                'quantity' => $item['quantity'],
                'harga_satuan' => $item['harga'],
                'sub_total' => $item['sub_total']
            ];

            $detail_id = $detailPesananModel->create($dataDetailPesanan);
            if (!$detail_id || !$varianModel->reduceStock($item['varian_id'], $item['quantity'])) {
                $detailPesananModel->deleteByPesananId($pesanan_id);
                $pesananModel->delete($pesanan_id);
                \App\Core\Flasher::setFlash('Gagal memproses salah satu item pesanan.', 'Produk', 'danger');
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }
            $createdDetails = true;
        }

        if ($createdDetails) {
            \App\Core\Flasher::setFlash('Pesanan berhasil dibuat! Silakan tunggu konfirmasi admin.', 'Produk', 'success');
            header('Location: ' . BASE_URL . '/profile/' . urlencode($_SESSION['username']) . '/history');
            exit;
        }

        $pesananModel->delete($pesanan_id);
        \App\Core\Flasher::setFlash('Gagal membuat detail pesanan!', 'Produk', 'danger');
        header('Location: ' . BASE_URL . '/checkout');
        exit;
    }

    /**
     * Upload bukti pembayaran
     */
    private function uploadPaymentProof($file)
    {
        $namaFile = $file['name'] ?? '';
        $ukuranFile = $file['size'] ?? 0;
        $error = $file['error'] ?? 4;
        $tmpName = $file['tmp_name'] ?? '';

        if ($error === 4) {
            return false;
        }

        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $pecahNamaFile = explode('.', $namaFile);
        $ekstensi = strtolower(end($pecahNamaFile));

        if (!in_array($ekstensi, $ekstensiValid)) {
            return false;
        }

        if ($ukuranFile > 2000000) {
            return false;
        }

        $namaFileBaru = 'bukti_' . uniqid() . '.' . $ekstensi;
        $uploadPath = __DIR__ . '/../../public/images/payments/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (move_uploaded_file($tmpName, $uploadPath . $namaFileBaru)) {
            return $namaFileBaru;
        }

        return false;
    }
}
