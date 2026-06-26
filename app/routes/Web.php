<?php

use App\Controllers\Authentication;
use App\Controllers\Home;
use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\OrderController;
use App\Core\Route;


class Web extends Route
{
    public function __construct()
    {
        $this->get('/', [Home::class, 'index']);

        // Authentication
        $this->get('/register', [Authentication::class, 'register']);
        $this->post('/register', [Authentication::class, 'store']);
        $this->get('/login', [Authentication::class, 'login']);
        $this->post('/login', [Authentication::class, 'authenticate']);
        $this->get('/logout', [Authentication::class, 'logout']);

        // Product
        $this->get('/products', [ProductController::class, 'index']);
        $this->get('/detail/{id}', [ProductController::class, 'detail']);
        $this->get('/checkout-product/{id}/{qty}', [ProductController::class, 'checkout']);

        // user
        $this->get('/profile/{username}', [UserController::class, 'index']);
        // Dashboard
        $this->get('/dashboard', [DashboardController::class, 'index']);

        //Product
        $this->get('/dashboard/products', [ProductController::class, 'index']);
        $this->get('/dashboard/products/add', [ProductController::class, 'create']);
        $this->get('/dashboard/products/edit/{id}', [ProductController::class, 'edit']);
        $this->post('/dashboard/products/store', [ProductController::class, 'store']);
        $this->post('/dashboard/products/update', [ProductController::class, 'update']);
        $this->get('/dashboard/products/delete/{id}', [ProductController::class, 'delete']);

        //Category
        $this->get('/dashboard/categories', [CategoryController::class, 'index']);
        $this->get('/dashboard/categories/add', [CategoryController::class, 'create']);
        $this->get('/dashboard/categories/edit/{id}', [CategoryController::class, 'edit']);
        $this->post('/dashboard/categories/store', [CategoryController::class, 'store']);
        $this->post('/dashboard/categories/update', [CategoryController::class, 'update']);
        $this->get('/dashboard/categories/delete/{id}', [CategoryController::class, 'delete']);

        //Orders
        $this->get('/dashboard/orders', [OrderController::class, 'index']);
        $this->get('/dashboard/orders/detail/{id}', [OrderController::class, 'detail']);
        $this->post('/dashboard/orders/update-status', [OrderController::class, 'updateStatus']);
    }
}