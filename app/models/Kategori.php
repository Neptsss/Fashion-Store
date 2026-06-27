<?php

class Kategori
{
    private $table = 'kategori';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function cekProdukKategori($id)
    {
        $this->db->query(
            'SELECT COUNT(*) as total
         FROM produk
         WHERE kategori_id = :id'
        );

        $this->db->bind('id', $id);

        return $this->db->single();
    }

    public function searchKategori($search)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE nama LIKE :search");
        $this->db->bind('search', '%' . $search . '%');
        return $this->db->resultSet();
    }


    public function getAllKategori()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getKategoriById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function createKategori($data)
    {
        $this->db->query('INSERT INTO ' . $this->table . ' (nama) VALUES (:nama)');
        $this->db->bind('nama', $data['nama']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateKategori($id, $data)
    {
        $this->db->query('UPDATE ' . $this->table . ' SET nama=:nama WHERE id=:id');
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('id', $id);
        $this->db->execute();
        return true;
    }

    public function deleteKategori($id)
    {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function kategoriExists($nama)
    {
        $this->db->query("
        SELECT id
        FROM kategori
        WHERE LOWER(nama) = LOWER(:nama)
    ");

        $this->db->bind('nama', $nama);

        return $this->db->single();
    }
    public function kategoriExistsExceptId($nama, $id)
    {
        $this->db->query("
        SELECT id
        FROM kategori
        WHERE LOWER(nama) = LOWER(:nama)
        AND id != :id
    ");

        $this->db->bind('nama', $nama);
        $this->db->bind('id', $id);

        return $this->db->single();
    }
}
