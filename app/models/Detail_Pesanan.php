<?php

class Detail_Pesanan
{
    private $table = 'detail_pesanan';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil semua detail pesanan
     */
    public function getAll()
    {
        $this->db->query("SELECT * FROM " . $this->table);
        return $this->db->resultSet();
    }

    /**
     * Mengambil detail pesanan berdasarkan ID
     */
    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Mengambil semua detail berdasarkan pesanan_id
     */
    public function getByPesananId($pesanan_id)
    {
        $this->db->query(
            "SELECT dp.*, p.nama, p.foto, vp.ukuran
             FROM " . $this->table . " dp
             LEFT JOIN varian_produk vp ON dp.varian_id = vp.id
             LEFT JOIN produk p ON vp.produk_id = p.id
             WHERE dp.pesanan_id = :pesanan_id
             ORDER BY dp.id ASC"
        );
        $this->db->bind(':pesanan_id', $pesanan_id);
        return $this->db->resultSet();
    }

    /**
     * Membuat detail pesanan
     */
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . "
                (pesanan_id, varian_id, quantity, harga_satuan, sub_total)
                VALUES
                (:pesanan_id, :varian_id, :quantity, :harga_satuan, :sub_total)";

        $this->db->query($query);
        $this->db->bind(':pesanan_id', $data['pesanan_id']);
        $this->db->bind(':varian_id', $data['varian_id'] ?? null);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':harga_satuan', $data['harga_satuan']);
        $this->db->bind(':sub_total', $data['sub_total']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update detail pesanan
     */
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . "
                SET
                    pesanan_id = :pesanan_id,
                    varian_id = :varian_id,
                    quantity = :quantity,
                    harga_satuan = :harga_satuan,
                    sub_total = :sub_total
                WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':pesanan_id', $data['pesanan_id']);
        $this->db->bind(':varian_id', $data['varian_id'] ?? null);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':harga_satuan', $data['harga_satuan']);
        $this->db->bind(':sub_total', $data['sub_total']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Hapus detail pesanan
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Hapus semua detail berdasarkan pesanan_id
     */
    public function deleteByPesananId($pesanan_id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE pesanan_id = :pesanan_id");
        $this->db->bind(':pesanan_id', $pesanan_id);
        return $this->db->execute();
    }

    /**
     * Menghitung jumlah item dalam pesanan
     */
    public function countItemByPesananId($pesanan_id)
    {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM " . $this->table . " 
             WHERE pesanan_id = :pesanan_id"
        );
        $this->db->bind(':pesanan_id', $pesanan_id);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    /**
     * Menghitung total quantity dalam pesanan
     */
    public function getTotalQuantityByPesananId($pesanan_id)
    {
        $this->db->query(
            "SELECT SUM(quantity) AS total_qty FROM " . $this->table . " 
             WHERE pesanan_id = :pesanan_id"
        );
        $this->db->bind(':pesanan_id', $pesanan_id);
        $result = $this->db->single();
        return $result['total_qty'] ?? 0;
    }

    /**
     * Mendapatkan detail pesanan dengan informasi produk lengkap
     */
    public function getDetailWithProduct($detail_id)
    {
        $this->db->query(
            "SELECT dp.*, p.nama, p.foto, p.harga, vp.ukuran
             FROM " . $this->table . " dp
             LEFT JOIN varian_produk vp ON dp.varian_id = vp.id
             LEFT JOIN produk p ON vp.produk_id = p.id
             WHERE dp.id = :id"
        );
        $this->db->bind(':id', $detail_id);
        return $this->db->single();
    }
}
