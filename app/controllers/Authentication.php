<?php 
class Authentication extends Controller{
    public function login(){
        $this->view('templates/header',['judul'=>"Login | Stellar & Co."]);
        $this->view('authentication/login');
        $this->view('templates/footer');
    }
    public function register(){
        $this->view('templates/header',['judul'=>"Regsiter | Stellar & Co."]);
        $this->view('authentication/register');
        $this->view('templates/footer');
    }
}