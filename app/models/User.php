<?php

class User
{
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUser()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getUserByUsername($username)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username=:username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function createUser($data)
    {
        $this->db->query('INSERT INTO ' . $this->table . ' (nama_lengkap,username,email,password) VALUES (:nama_lengkap,:username,:email,:password)');

        $this->db->bind('nama_lengkap', $data['nama_lengkap']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $data['password']);

        $this->db->execute();

        return true;
    }
}
