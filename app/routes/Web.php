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
        $this->get('/login',[Authentication::class, 'index']);
        $this->post('/login',[Authentication::class, 'login']);

        $this->get('/register',[Authentication::class,'register']);
        $this->post('/register',[Authentication::class,'userRegister']);
        
        $this->get('/logout', [Authentication::class, 'logout']);

        $this->get('/products',[ProductController::class,'index']);
        $this->get('/detail/{id}',[ProductController::class, 'detail']);

        // user
        $this->get('/profile/{username}',[UserController::class,'index']);
    }
}