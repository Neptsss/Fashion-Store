<?php

namespace App\Controllers;

use App\Core\Controller;
class ProductController extends Controller{
    public function index(){
        $this->view('templates/header', ['judul'=>"Products | Stellar & Co."]);
        $this->view('partials/navbar');
        $this->view('products/products');
        $this->view('templates/footer');
    }

    public function detail(){
        $this->view('templates/header', ['judul' => "Detail Product | Stellar & Co."]);
        $this->view('partials/navbar');
        $this->view('products/detail');
        $this->view('templates/footer');
    }

    public function checkout(){
        $this->view('templates/header', ['judul' => "Checkout Product | Stellar & Co."]);
        $this->view('partials/navbar');
        $this->view('products/checkout');
        $this->view('templates/footer');
    }
}