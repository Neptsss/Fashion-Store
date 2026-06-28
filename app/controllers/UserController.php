<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;

class UserController extends Controller
{
    private $userModel;
    private $pesananModel;
    private $detailPesananModel;

    public function __construct()
    {
        $this->userModel = $this->model('user');
        $this->pesananModel = $this->model('pesanan');
        $this->detailPesananModel = $this->model('detail_pesanan');
    }
    public function index($username = null)
    {
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $this->view('templates/header', [
            'judul' => 'Profile | ' . $user['nama_lengkap']
        ]);

        $this->view('partials/navbar');

        $this->view('pembeli/profile', [
            'user' => $user
        ]);

        $this->view('templates/footer');
    }

    public function history($username = null)
    {
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $pesananUser = $this->pesananModel->getUserPesananWithDetails($user['id']);

        foreach ($pesananUser as &$pesanan) {
            $details = $this->detailPesananModel->getByPesananId($pesanan['id']);
            $pesanan['items'] = $details;
            $pesanan['item_count'] = count($details);
            $pesanan['product_name'] = !empty($details) ? $details[0]['nama'] : null;
            $pesanan['product_varian'] = !empty($details) ? ($details[0]['ukuran'] ?? null) : null;
            $pesanan['product_qty'] = !empty($details) ? ($details[0]['quantity'] ?? null) : null;
            $pesanan['product_image'] = !empty($details) ? ($details[0]['foto'] ?? null) : null;
        }
        unset($pesanan);

        $this->view('templates/header', ['judul' => 'History | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/history', ['pesananUser' => $pesananUser, 'user' => $user]);
        $this->view('templates/footer');
    }

    public function editProfile($username = null)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== $username) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $user = $this->userModel->getUserByUsername($username);
        if (!$user) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $this->view('templates/header', ['judul' => 'Edit Profil | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/profile_edit', ['user' => $user]);
        $this->view('templates/footer');
    }

    private function uploadProfileImage()
    {
        $namaFile = $_FILES['foto']['name'] ?? '';
        $ukuranFile = $_FILES['foto']['size'] ?? 0;
        $error = $_FILES['foto']['error'] ?? 4;
        $tmpName = $_FILES['foto']['tmp_name'] ?? '';

        if ($error === 4) {
            return null;
        }

        $validExtensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($extension, $validExtensions)) {
            return false;
        }
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            return false;
        }

        if ($ukuranFile > 2000000) {
            return false;
        }

        $filename = uniqid('user_') . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/images/users/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if (move_uploaded_file($tmpName, $uploadPath . $filename)) {
            return $filename;
        }

        return false;
    }

    public function updateProfile($username = null)
    {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
            exit;
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== $username) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $user = $this->userModel->getUserByUsername($username);
        if (!$user) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $data = [
            'id' => $user['id'],
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? $user['nama_lengkap']),
        ];
        if ($_SESSION['role'] === 'Pembeli') {
            $data['telp'] = trim($_POST['telp'] ?? '');
            $data['alamat'] = trim($_POST['alamat'] ?? '');
        }
        // Validasi Nama Lengkap
        if (empty($data['nama_lengkap'])) {
            Flasher::setFlash(
                'Nama lengkap wajib diisi.',
                'Update Profil',
                'danger'
            );

            header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
            exit;
        }

        if ($_SESSION['role'] === 'Pembeli') {
            
            if(!empty($data['telp'])){
            if (!ctype_digit($data['telp'])) {
                Flasher::setFlash(
                    'Nomor telepon hanya boleh berisi angka.',
                    'Update Profil',
                    'danger'
                );
               

                header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
                exit;
            }

            if (strlen($data['telp']) < 10 || strlen($data['telp']) > 15) {
                Flasher::setFlash(
                    'Nomor telepon harus terdiri dari 10 sampai 15 digit.',
                    'Update Profil',
                    'danger'
                );

                header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
                exit;
            }
            }

          
        }
      

        $uploadedImage = $this->uploadProfileImage();
        
        if ($uploadedImage === false) {
            Flasher::setFlash(
                'Foto profil tidak valid. Pastikan file merupakan gambar JPG atau PNG dengan ukuran maksimal 2 MB.',
                'Update Profil',
                'danger'
            );
            header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
            exit;
        }

        if (is_string($uploadedImage)) {
            $data['foto'] = $uploadedImage;
            if (!empty($user['foto'])) {
                $oldPath = __DIR__ . '/../../public/images/users/' . $user['foto'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        if ($this->userModel->updateUser($data['id'], $data)) {
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['telp'] = $data['telp'];
            $_SESSION['alamat'] = $data['alamat'];
            if (isset($data['foto'])) {
                $_SESSION['foto'] = $data['foto'];
            }
            Flasher::setFlash('Profil berhasil diperbarui.', 'Update Profil', 'success');
        } else {
            Flasher::setFlash('Gagal memperbarui profil.', 'Update Profil', 'danger');
        }

        header('Location: ' . BASE_URL . '/profile/' . urlencode($username));
        exit;
    }

    public function detail_transaksi($username = null, $pesanan_id = null)
    {

        $user = $this->userModel->getUserByUsername($username);

        if (!$user || !$pesanan_id) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $pesanan = $this->pesananModel->getWithDetails($pesanan_id);
        if (!$pesanan || $pesanan['user_id'] !== $user['id']) {
            header('Location: ' . BASE_URL . '/profile/' . $username . '/history');
            exit;
        }

        $details = $this->detailPesananModel->getByPesananId($pesanan_id);
        $productTotal = 0;
        foreach ($details as $item) {
            $productTotal += $item['sub_total'];
        }
        $shipping = max(0, $pesanan['total_harga'] - $productTotal);

        $this->view('templates/header', ['judul' => 'Transaksi Detail | Stellar & Co.']);
        $this->view('partials/navbar');
        $this->view('pembeli/detail_transaksi', [
            'user' => $user,
            'pesanan' => $pesanan,
            'details' => $details,
            'productTotal' => $productTotal,
            'shipping' => $shipping,
        ]);
        $this->view('templates/footer');
    }

    public function cancelOrder($username, $id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $pesananModel = $this->model('Pesanan');
        $detailModel = $this->model('Detail_Pesanan');
        $varianModel = $this->model('VarianProduk');

        $pesanan = $pesananModel->getById($id);

        if (!$pesanan) {
            \App\Core\Flasher::setFlash('Pesanan tidak ditemukan.', 'Error', 'danger');
            header('Location: ' . BASE_URL . '/profile/' . urlencode($username) . '/history');
            exit;
        }

        if ($pesanan['user_id'] != $_SESSION['user_id']) {
            \App\Core\Flasher::setFlash('Akses ditolak.', 'Error', 'danger');
            header('Location: ' . BASE_URL . '/profile/' . urlencode($username) . '/history');
            exit;
        }

        if ($pesanan['status'] !== 'Diproses') {
            \App\Core\Flasher::setFlash('Pesanan tidak dapat dibatalkan.', 'Error', 'warning');
            header('Location: ' . BASE_URL . '/profile/' . urlencode($username) . '/history');
            exit;
        }

        $detailPesanan = $detailModel->getByPesananId($id);

        foreach ($detailPesanan as $detail) {
            $varianModel->addStock(
                $detail['varian_id'],
                $detail['quantity']
            );
        }

        $pesananModel->updateStatus($id, 'Dibatalkan');

        \App\Core\Flasher::setFlash('Pesanan berhasil dibatalkan.', 'Pesanan', 'success');
        header('Location: ' . BASE_URL . '/profile/' . urlencode($username) . '/history');
        exit;
    }
}
