<?php


class Home extends Controller
{
    public function index($nama = 'Noval', $pekerjaan = "Mahasiswa")
    {
        $this->view('templates/header',[
            'judul' => "Home | Stellar & Co"
        ]);
        $this->view('partials/navbar');
        $this->view('landing');
        $this->view('templates/footer');
    }
}
