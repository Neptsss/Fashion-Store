<?php

use App\Controllers\Authentication;
use App\Controllers\Home;
use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\OrderController;
use App\Controllers\PenjualController;
use App\Core\AuthMiddleware;
use App\Core\Route;


class Web extends Route
{
    public function __construct()
    {
        $this->get('/', [Home::class, 'index']);

        // Authentication
        $this->get('/login', [Authentication::class, 'index']);
        $this->post('/login', [Authentication::class, 'login']);

        $this->get('/register', [Authentication::class, 'register']);
        $this->post('/register', [Authentication::class, 'store']);

        $this->get('/logout', [Authentication::class, 'logout']);

        // Product
        $this->get('/products', [ProductController::class, 'index']);
        $this->get('/detail/{id}', [ProductController::class, 'detail']);
        $this->get('/checkout/{id}/{qty}/{varian}', [ProductController::class, 'checkout'], ['auth', 'pembeli']);
        $this->post('/proses-checkout', [ProductController::class, 'prosesCheckout'], ['auth', 'pembeli']);

        // user
        $this->get('/profile/{username}',  [UserController::class,  'index'], ['auth']);
        $this->get('/profile/{username}/edit', [UserController::class, 'editProfile'], ['auth']);
        $this->post('/profile/{username}/update', [UserController::class, 'updateProfile'], ['auth']);
        $this->get('/profile/{username}/history', [UserController::class, 'history'], ['auth', 'pembeli']);
        $this->get('/profile/{username}/history/{id}', [UserController::class, 'detail_transaksi'], ['auth', 'pembeli']);
        $this->post('/profile/{username}/order/cancel/{id}', [UserController::class, 'cancelOrder'], ['auth', 'pembeli']);

        // Dashboard
        $this->get('/dashboard', [DashboardController::class, 'index'],  ['penjual']);

        //Product
        $this->get('/dashboard/products', [PenjualController::class, 'index'],  ['penjual']);
        $this->get('/dashboard/products/add', [PenjualController::class, 'create'],  ['penjual']);
        $this->get('/dashboard/products/edit/{id}', [PenjualController::class, 'edit'],  ['penjual']);
        $this->post('/dashboard/products/store', [PenjualController::class, 'store'],  ['penjual']);
        $this->post('/dashboard/products/update', [PenjualController::class, 'update'],  ['penjual']);
        $this->get('/dashboard/products/delete/{id}', [PenjualController::class, 'delete'],  ['penjual']);

        //Category
        $this->get('/dashboard/categories', [CategoryController::class, 'index'],  ['penjual']);
        $this->get('/dashboard/categories/add', [CategoryController::class, 'create'],  ['penjual']);
        $this->get('/dashboard/categories/edit/{id}', [CategoryController::class, 'edit'],  ['penjual']);
        $this->post('/dashboard/categories/store', [CategoryController::class, 'store'],  ['penjual']);
        $this->post('/dashboard/categories/update', [CategoryController::class, 'update'],  ['penjual']);
        $this->get('/dashboard/categories/delete/{id}', [CategoryController::class, 'delete'],  ['penjual']);

        //Orders
        $this->get('/dashboard/orders', [OrderController::class, 'index'],  ['penjual']);
        $this->get('/dashboard/orders/detail/{id}', [OrderController::class, 'detail'],  ['penjual']);
        $this->post('/dashboard/orders/update-status', [OrderController::class, 'updateStatus'],  ['penjual']);
    }
}
