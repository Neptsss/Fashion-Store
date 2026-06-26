<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::isPenjual();
    }

    public function index()
    {
        $data['judul'] = 'Dashboard | Stellar & Co';
        $dashboardModel = $this->model('DashboardModel');
        $data['stats'] = $dashboardModel->getStats();
        $data['chart_data'] = json_encode($dashboardModel->getSalesChart());

        $this->view('penjual/layout/header', $data);
        $this->view('penjual/dashboard', $data);
        $this->view('penjual/layout/footer', $data);
    }
}
