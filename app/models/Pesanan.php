<?php

class Pesanan
{
    private $table = 'pesanan';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil semua pesanan
     */
    public function getAll()
    {
        $this->db->query("SELECT p.*, u.nama_lengkap as nama_user FROM {$this->table} p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil pesanan berdasarkan ID
     */
    public function getById($id)
    {
        $this->db->query("SELECT p.*, u.nama_lengkap as nama_user FROM {$this->table} p JOIN users u ON p.user_id = u.id WHERE p.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function searchPesanan($search){
        $this->db->query("SELECT p.*, u.nama_lengkap as nama_user FROM {$this->table} p JOIN users u ON p.user_id = u.id WHERE p.id LIKE :search OR u.nama_lengkap LIKE :search ORDER BY p.created_at DESC");
        $this->db->bind('search', '%' . $search . '%');
        return $this->db->resultSet();
    }
    /**
     * Mengambil pesanan berdasarkan user_id
     */
    public function getByUserId($user_id)
    {
        $this->db->query(
            "SELECT * FROM " . $this->table . " 
             WHERE user_id = :user_id 
             ORDER BY tgl_pemesanan DESC"
        );
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    /**
     * Membuat pesanan baru
     */
    public function create($data)
    {
            $query = "INSERT INTO " . $this->table . "
                (user_id, tgl_pemesanan, total_harga, bukti_pembayaran, status)
                VALUES
                (:user_id, :tgl_pemesanan, :total_harga, :bukti_pembayaran, :status)";

            $this->db->query($query);

            $tanggalSekarang = date('Y-m-d H:i:s');

            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':tgl_pemesanan', $tanggalSekarang);
            $this->db->bind(':total_harga', $data['total_harga']);
            $this->db->bind(':bukti_pembayaran', $data['bukti_pembayaran'] ?? null);
            $this->db->bind(':status', $data['status'] ?? 'Diproses');

            if ($this->db->execute()) {
                
                return $this->db->lastInsertId();
            }
      
            return false;
    }
    /**
     * Update pesanan
     */
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . "
                SET
                    user_id = :user_id,
                    total_harga = :total_harga,
                    bukti_pembayaran = :bukti_pembayaran,
                    status = :status
                WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':total_harga', $data['total_harga']);
        $this->db->bind(':bukti_pembayaran', $data['bukti_pembayaran'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'Diproses');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Update status pesanan
     */
    public function updateStatus($id, $status)
    {
        $this->db->query(
            "UPDATE " . $this->table . " 
             SET status = :status 
             WHERE id = :id"
        );
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Hapus pesanan
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Hitung total pesanan berdasarkan user
     */
    public function countByUserId($user_id)
    {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM " . $this->table . " 
             WHERE user_id = :user_id"
        );
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    /**
     * Hitung total pesanan berdasarkan status
     */
    public function countByStatus($status)
    {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM " . $this->table . " 
             WHERE status = :status"
        );
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    /**
     * Mendapatkan pesanan dengan detail lengkap
     */
    public function getWithDetails($pesanan_id)
    {
        $this->db->query(
            "SELECT p.*, u.nama_lengkap, u.email, u.telp AS nomor_telepon
             FROM " . $this->table . " p
             JOIN users u ON p.user_id = u.id
             WHERE p.id = :id"
        );
        $this->db->bind(':id', $pesanan_id);
        return $this->db->single();
    }

    /**
     * Mendapatkan pesanan dengan detail lengkap ( dashboard )
     */
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

    /**
     * Mendapatkan semua pesanan user dengan details
     */
    public function getUserPesananWithDetails($user_id)
    {
        $this->db->query(
            "SELECT p.*, u.nama_lengkap, u.email, u.telp
             FROM " . $this->table . " p
             JOIN users u ON p.user_id = u.id
             WHERE p.user_id = :user_id
             ORDER BY p.id DESC"
        );
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }
}
