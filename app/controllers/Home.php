<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller
{
    public function index($id =null)
    {
       
        $this->view('templates/header',[
            'judul' => "Home | Stellar & Co"
        ]);
        $this->view('partials/navbar');
        $this->view('landing');
        $this->view('templates/footer');
    }
}
