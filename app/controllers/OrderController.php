<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flasher;
use App\Core\AuthMiddleware;

class OrderController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::isPenjual();
    }

    public function index()
    {
        $data['judul'] = 'Orders | Stellar & Co';
        $data['activeMenu'] = "orders";
        if (isset($_GET['search'])) {
            $data['pesanan'] = $this->model('pesanan')->searchPesanan($_GET['search']);
        } else {
            $data['pesanan'] = $this->model('Pesanan')->getAll();
        }

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/pesanan/index', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Pesanan | Stellar & Co';
        $data['pesanan'] = $this->model('Pesanan')->getById($id);
        $data['detail_pesanan'] = $this->model('Pesanan')->getDetailPesanan($id);

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/pesanan/detail', $data);
        $this->view('penjual/layout/footer', $data);
    }

    public function updateStatus()
    {
        $id = $_POST['id'];
        $status = $_POST['status'];

        if ($this->model('Pesanan')->updateStatus($id, $status) > 0) {
            Flasher::setFlash('Order status successfully', 'updated', 'success');
        } else {
            Flasher::setFlash('Failed to update', 'order status', 'danger');
        }

        header('Location: ' . BASE_URL . '/dashboard/orders');
        exit;
    }
}
