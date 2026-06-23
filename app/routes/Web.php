<?php

use App\Controllers\Authentication;
use App\Controllers\Home;
use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Core\Route;


class Web extends Route{
    public function __construct()
    {
        $this->get('/', [Home::class, 'index']);

        // Authentication
       

        // Product
        $this->get('/products',[ProductController::class,'index']);
        $this->get('/detail/{id}',[ProductController::class, 'detail']);
        $this->get('/checkout-product/{id}/{qty}',[ProductController::class,'checkout']);

        // user
        $this->get('/profile/{username}',[UserController::class,'index']);
    }
}