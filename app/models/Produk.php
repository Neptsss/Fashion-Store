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
        $this->db->query('SELECT p.*, v.stok, v.ukuran FROM ' . $this->table . ' p LEFT JOIN varian_produk v ON p.id = v.produk_id');
        return $this->db->resultSet();
    }

    public function getProdukLimit($limit = 5)
    {
        $this->db->query('SELECT p.*, v.stok, v.ukuran FROM ' . $this->table . ' p LEFT JOIN varian_produk v ON p.id = v.produk_id LIMIT :limit');
        $this->db->bind(':limit', $limit);

        return $this->db->resultSet();
    }

    public function getProdukById($id)
    {
        $this->db->query('SELECT p.*, v.stok, v.ukuran FROM ' . $this->table . ' p LEFT JOIN varian_produk v ON p.id = v.produk_id WHERE p.id = :id');
        $this->db->bind('id', $id);

        return $this->db->single();
    }

    public function searchProduk($keyword)
    {
        $this->db->query(
            "SELECT p.*, k.nama AS kategori, v.stok, v.ukuran
         FROM produk p
         JOIN kategori k
            ON p.kategori_id = k.id
         LEFT JOIN varian_produk v
            ON p.id = v.produk_id
         WHERE p.nama LIKE :keyword
            OR k.nama LIKE :keyword"
        );

        $this->db->bind('keyword', '%' . $keyword . '%');

        return $this->db->resultSet();
    }

    public function createProduk($data)
    {
        $query = "INSERT INTO {$this->table}
                (nama, kategori_id, harga, foto, deskripsi)
                VALUES
                (:nama, :kategori_id, :harga, :foto, :deskripsi)";

        $this->db->query($query);

        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('harga', $data['harga']);
        $this->db->bind('foto', $data['foto']);
        $this->db->bind('deskripsi', $data['deskripsi']);

        $this->db->execute();
        $produk_id = $this->db->lastInsertId();

        if ($produk_id) {
            $queryVarian = "INSERT INTO varian_produk (produk_id, ukuran, stok) VALUES (:produk_id, :ukuran, :stok)";
            $this->db->query($queryVarian);
            $this->db->bind('produk_id', $produk_id);
            $this->db->bind('ukuran', $data['ukuran']);
            $this->db->bind('stok', $data['stok']);
            $this->db->execute();
        }

        return $this->db->rowCount();
    }

    public function updateProduk($id, $data)
    {
        $query = "UPDATE {$this->table}
                SET
                    nama = :nama,
                    kategori_id = :kategori_id,
                    harga = :harga,
                    foto = :foto,
                    deskripsi = :deskripsi
                WHERE id = :id";

        $this->db->query($query);

        $this->db->bind('nama', $data['nama']);
        $this->db->bind('kategori_id', $data['kategori_id']);
        $this->db->bind('harga', $data['harga']);
        $this->db->bind('foto', $data['foto']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('id', $id);

        $this->db->execute();

        // Check varian
        $this->db->query("SELECT id FROM varian_produk WHERE produk_id = :produk_id");
        $this->db->bind('produk_id', $id);
        $varian = $this->db->single();

        if ($varian) {
            $queryVarian = "UPDATE varian_produk SET ukuran = :ukuran, stok = :stok WHERE produk_id = :produk_id";
        } else {
            $queryVarian = "INSERT INTO varian_produk (produk_id, ukuran, stok) VALUES (:produk_id, :ukuran, :stok)";
        }
        $this->db->query($queryVarian);
        $this->db->bind('produk_id', $id);
        $this->db->bind('ukuran', $data['ukuran']);
        $this->db->bind('stok', $data['stok']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteProduk($id)
    {
        $this->db->query('DELETE FROM varian_produk WHERE produk_id = :id');
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);

        $this->db->execute();

        return true;
    }
}