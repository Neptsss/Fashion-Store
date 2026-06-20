<?php


class Home extends Controller
{
    public function index($nama = 'Noval', $pekerjaan = "Mahasiswa")
    {
        $this->view('templates/header',[
            'judul' => "Home"
        ]);
        $this->view('landing', [
            "nama" => $this->model('User')->getAllUser(),
            "pekerjaan" => $pekerjaan,
        ]);
        $this->view('templates/footer');
    }
}
