<?php

class VarianProduk
{
    private $table = 'varian_produk';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil semua varian berdasarkan produk_id
     */
    public function getByProdukId($produk_id)
    {
        $this->db->query(
            "SELECT * FROM " . $this->table . " 
             WHERE produk_id = :produk_id
             ORDER BY ukuran ASC"
        );
        $this->db->bind(':produk_id', $produk_id);
        return $this->db->resultSet();
    }

    /**
     * Mengambil varian berdasarkan ID
     */
    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Mengambil varian dengan detail produk
     */
    public function getWithProduk($id)
    {
        $this->db->query(
            "SELECT vp.*, p.nama, p.harga, p.foto, p.deskripsi
             FROM " . $this->table . " vp
             JOIN produk p ON vp.produk_id = p.id
             WHERE vp.id = :id"
        );
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Membuat varian produk baru
     */
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . "
                (produk_id, ukuran, stok)
                VALUES
                (:produk_id, :ukuran, :stok)";

        $this->db->query($query);
        $this->db->bind(':produk_id', $data['produk_id']);
        $this->db->bind(':ukuran', $data['ukuran']);
        $this->db->bind(':stok', $data['stok']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update varian produk
     */
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . "
                SET
                    ukuran = :ukuran,
                    stok = :stok
                WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':ukuran', $data['ukuran']);
        $this->db->bind(':stok', $data['stok']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Menghapus varian produk
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Menghapus semua varian berdasarkan produk_id
     */
    public function deleteByProdukId($produk_id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE produk_id = :produk_id");
        $this->db->bind(':produk_id', $produk_id);
        return $this->db->execute();
    }

    /**
     * Cek stok tersedia
     */
    public function isStockAvailable($id, $quantity)
    {
        $varian = $this->getById($id);
       
        return $varian && $varian['stok'] >= $quantity;
    }

    /**
     * Kurangi stok varian
     */
    public function reduceStock($id, $quantity)
    {
        if (!$this->isStockAvailable($id, $quantity)) {
            return false;
        }

        $this->db->query(
            "UPDATE " . $this->table . " 
             SET stok = stok - :quantity 
             WHERE id = :id"
        );
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Tambah stok varian
     */
    public function addStock($id, $quantity)
    {
        $this->db->query(
            "UPDATE " . $this->table . " 
             SET stok = stok + :quantity 
             WHERE id = :id"
        );
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Set stok ke nilai tertentu
     */
    public function setStock($id, $stock)
    {
        $this->db->query(
            "UPDATE " . $this->table . " 
             SET stok = :stock 
             WHERE id = :id"
        );
        $this->db->bind(':stock', $stock);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Dapatkan total stok dari satu produk
     */
    public function getTotalStockByProdukId($produk_id)
    {
        $this->db->query(
            "SELECT SUM(stok) AS total_stok FROM " . $this->table . " 
             WHERE produk_id = :produk_id"
        );
        $this->db->bind(':produk_id', $produk_id);
        $result = $this->db->single();
        return $result->total_stok ?? 0;
    }

    /**
     * Hitung jumlah varian untuk produk tertentu
     */
    public function countByProdukId($produk_id)
    {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM " . $this->table . " 
             WHERE produk_id = :produk_id"
        );
        $this->db->bind(':produk_id', $produk_id);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    /**
     * Cek apakah ukuran sudah ada untuk produk tertentu
     */
    public function sizeExists($produk_id, $ukuran)
    {
        $this->db->query(
            "SELECT id FROM " . $this->table . " 
             WHERE produk_id = :produk_id AND ukuran = :ukuran"
        );
        $this->db->bind(':produk_id', $produk_id);
        $this->db->bind(':ukuran', $ukuran);
        $result = $this->db->single();
        return $result !== null;
    }

    /**
     * Mendapatkan varian berdasarkan ukuran
     */
    public function getByUkuran($produk_id, $ukuran)
    {
        $this->db->query(
            "SELECT * FROM " . $this->table . " 
             WHERE produk_id = :produk_id AND ukuran = :ukuran"
        );
        $this->db->bind(':produk_id', $produk_id);
        $this->db->bind(':ukuran', $ukuran);
        return $this->db->single();
    }

    /**
     * Mendapatkan stok untuk varian tertentu
     */
    public function getStockById($id)
    {
        $this->db->query("SELECT stok FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return $result->stok ?? 0;
    }
}
