<?php

class Produk
{
    private $table = 'produk';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllProduk()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getProdukById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);

        return $this->db->single();
    }

    public function searchProduk($keyword)
    {
        $this->db->query(
            "SELECT p.*, k.nama AS kategori
         FROM produk p
         JOIN kategori k
            ON p.kategori_id = k.id
         WHERE p.nama LIKE :keyword
            OR k.nama LIKE :keyword"
        );

        $this->db->bind('keyword', '%' . $keyword . '%');

        return $this->db->resultSet();
    }

    public function createProduk($data)
    {
        $query = "INSERT INTO {$this->table}
                (nama, kategori_id, stok, harga, foto, deskripsi, ukuran)
                VALUES
                (:nama, :kategori_id, :stok, :harga, :foto, :deskripsi, :ukuran)";

        $this->db->query($query);

        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('harga', $data['harga']);
        $this->db->bind('foto', $data['foto']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('ukuran', $data['ukuran']);

        $this->db->execute();

        return true;
    }

    public function updateProduk($id, $data)
    {
        $query = "UPDATE {$this->table}
                SET
                    nama = :nama,
                    kategori_id = :kategori_id,
                    stok = :stok,
                    harga = :harga,
                    foto = :foto,
                    deskripsi = :deskripsi,
                    ukuran = :ukuran
                WHERE id = :id";

        $this->db->query($query);

        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('harga', $data['harga']);
        $this->db->bind('foto', $data['foto']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('ukuran', $data['ukuran']);
        $this->db->bind('id', $id);

        $this->db->execute();

        return true;
    }

    public function deleteProduk($id)
    {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);

        $this->db->execute();

        return true;
    }
}