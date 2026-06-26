<?php

class Pesanan
{
    private $table = 'pesanan';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllPesanan()
    {
        $this->db->query("SELECT p.*, u.nama_lengkap as nama_user FROM {$this->table} p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    public function getPesananById($id)
    {
        $this->db->query("SELECT p.*, u.nama_lengkap as nama_user FROM {$this->table} p JOIN users u ON p.user_id = u.id WHERE p.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE {$this->table} SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getDetailPesanan($pesanan_id)
    {
        $this->db->query("SELECT dp.*, p.nama as nama_produk, p.foto 
                          FROM detail_pesanan dp 
                          JOIN varian_produk vp ON dp.varian_id = vp.id
                          JOIN produk p ON vp.produk_id = p.id
                          WHERE dp.pesanan_id = :pesanan_id");
        $this->db->bind('pesanan_id', $pesanan_id);
        return $this->db->resultSet();
    }
}
