<?php

class Produk
{
    private $table = 'produk';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil semua produk
     */
    public function getAllProduk()
    {
        $this->db->query("
        SELECT
            p.*,
            vp.id AS varian_id,
            vp.ukuran,
            vp.stok
        FROM produk p
        INNER JOIN varian_produk vp
            ON p.id = vp.produk_id
        ORDER BY p.created_at DESC
    ");

        return $this->db->resultSet();
    }

    /**
     * Mengambil produk dengan limit
     */
    public function getProdukLimit($limit = 5)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Mengambil produk berdasarkan ID
     */
    public function getProdukById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Mengambil produk dengan total stok dari semua varian
     */
    public function getProdukWithTotalStock($id)
    {
        $this->db->query(
            "SELECT 
                p.*,
                k.nama AS kategori,
                COALESCE(SUM(vp.stok), 0) AS stok_total
            FROM " . $this->table . " p
            LEFT JOIN kategori k ON p.kategori_id = k.id
            LEFT JOIN varian_produk vp ON p.id = vp.produk_id
            WHERE p.id = :id
            GROUP BY p.id"
        );
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Mengambil semua varian produk berdasarkan produk_id
     */
    public function getVarianProduk($produk_id)
    {
        $this->db->query(
            "SELECT * FROM varian_produk 
             WHERE produk_id = :produk_id
             ORDER BY ukuran ASC"
        );
        $this->db->bind(':produk_id', $produk_id);
        return $this->db->resultSet();
    }

    /**
     * Mengambil varian produk berdasarkan ID
     */
    public function getVarianProdukById($varian_id)
    {
        $this->db->query(
            "SELECT * FROM varian_produk WHERE id = :id"
        );
        $this->db->bind(':id', $varian_id);
        return $this->db->single();
    }

    public function getProdukVarianById($varian_id)
    {
        $this->db->query("
        SELECT
            p.*,
            vp.id AS varian_id,
            vp.ukuran,
            vp.stok
        FROM produk p
        INNER JOIN varian_produk vp
            ON p.id = vp.produk_id
        WHERE vp.id = :id
    ");

        $this->db->bind(':id', $varian_id);

        return $this->db->single();
    }

    /**
     * Mengambil total stok produk dari semua varian
     */
    public function getTotalStock($produk_id)
    {
        $this->db->query(
            "SELECT COALESCE(SUM(stok), 0) AS total_stok 
             FROM varian_produk 
             WHERE produk_id = :produk_id"
        );
        $this->db->bind(':produk_id', $produk_id);
        $result = $this->db->single();
        return $result->total_stok ?? 0;
    }

    /**
     * Mencari produk berdasarkan keyword
     */
    public function searchProduk($keyword)
    {
        $this->db->query("
        SELECT
            p.*,
            k.nama AS kategori,
            vp.id AS varian_id,
            vp.ukuran,
            vp.stok
        FROM produk p
        INNER JOIN kategori k
            ON p.kategori_id = k.id
        INNER JOIN varian_produk vp
            ON p.id = vp.produk_id

        WHERE
            p.nama LIKE :keyword
            OR p.deskripsi LIKE :keyword
            OR k.nama LIKE :keyword
            OR vp.ukuran LIKE :keyword

        ORDER BY p.created_at DESC
    ");

        $this->db->bind(':keyword', '%' . $keyword . '%');

        return $this->db->resultSet();
    }

    /**
     * Mencari produk berdasarkan kategori
     */
    public function getProdukByKategori($kategori_id)
    {
        $this->db->query(
            "SELECT p.*, k.nama AS kategori, COALESCE(SUM(vp.stok), 0) AS stok_total
             FROM " . $this->table . " p
             LEFT JOIN kategori k ON p.kategori_id = k.id
             LEFT JOIN varian_produk vp ON p.id = vp.produk_id
             WHERE p.kategori_id = :kategori_id
             GROUP BY p.id
             ORDER BY p.created_at DESC"
        );
        $this->db->bind(':kategori_id', $kategori_id);
        return $this->db->resultSet();
    }

    /**
     * Membuat produk baru (tanpa varian)
     */
    public function createProduk($data)
    {
        $query = "INSERT INTO " . $this->table . "
                (nama, kategori_id, harga, foto, deskripsi)
                VALUES
                (:nama, :kategori_id, :harga, :foto, :deskripsi)";

        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kategori_id', $data['kategori_id']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':foto', $data['foto'] ?? null);
        $this->db->bind(':deskripsi', $data['deskripsi']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update produk
     */
    public function updateProduk($id, $data)
    {
        $query = "UPDATE " . $this->table . "
                SET
                    nama = :nama,
                    kategori_id = :kategori_id,
                    harga = :harga,
                    foto = :foto,
                    deskripsi = :deskripsi,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kategori_id', $data['kategori_id']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':foto', $data['foto'] ?? null);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Membuat varian produk
     */
    public function createVarianProduk($produk_id, $data)
    {
        $query = "INSERT INTO varian_produk
                (produk_id, ukuran, stok)
                VALUES
                (:produk_id, :ukuran, :stok)";

        $this->db->query($query);
        $this->db->bind(':produk_id', $produk_id);
        $this->db->bind(':ukuran', $data['ukuran']);
        $this->db->bind(':stok', $data['stok']);

        return $this->db->execute();
    }

    /**
     * Update varian produk
     */
    public function updateVarianProduk($varian_id, $data)
    {
        $query = "UPDATE varian_produk
                SET
                    ukuran = :ukuran,
                    stok = :stok,
                    created_at = created_at
                WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':ukuran', $data['ukuran']);
        $this->db->bind(':stok', $data['stok']);
        $this->db->bind(':id', $varian_id);

        return $this->db->execute();
    }

    /**
     * Menghapus varian produk
     */
    public function deleteVarianProduk($varian_id)
    {
        $this->db->query('DELETE FROM varian_produk WHERE id = :id');
        $this->db->bind(':id', $varian_id);
        return $this->db->execute();
    }

    /**
     * Menghapus produk (beserta semua variannya)
     */
    public function deleteProduk($id)
    {
        $this->db->query('DELETE FROM varian_produk WHERE produk_id = :produk_id');
        $this->db->bind(':produk_id', $id);
        $this->db->execute();

        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Cek stok varian produk
     */
    public function checkStockVarian($varian_id, $quantity)
    {
        $varian = $this->getVarianProdukById($varian_id);
        return $varian && $varian->stok >= $quantity;
    }

    /**
     * Kurangi stok varian produk
     */
    public function reduceStockVarian($varian_id, $quantity)
    {
        $this->db->query(
            "UPDATE varian_produk 
             SET stok = stok - :quantity 
             WHERE id = :id AND stok >= :quantity"
        );
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $varian_id);

        return $this->db->execute();
    }

    /**
     * Tambah stok varian produk
     */
    public function addStockVarian($varian_id, $quantity)
    {
        $this->db->query(
            "UPDATE varian_produk 
             SET stok = stok + :quantity 
             WHERE id = :id"
        );
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $varian_id);

        return $this->db->execute();
    }

    /**
     * Mengambil produk dengan pagination
     */
    public function getProdukPaginated($limit = 12, $offset = 0)
    {
        $this->db->query(
            "SELECT p.*, k.nama AS kategori, COALESCE(SUM(vp.stok), 0) AS stok_total
             FROM " . $this->table . " p
             LEFT JOIN kategori k ON p.kategori_id = k.id
             LEFT JOIN varian_produk vp ON p.id = vp.produk_id
             GROUP BY p.id
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    /**
     * Hitung total produk
     */
    public function countProduk()
    {
        $this->db->query('SELECT COUNT(*) AS total FROM ' . $this->table);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    /**
     * Hitung total produk berdasarkan kategori
     */
    public function countProdukByKategori($kategori_id)
    {
        $this->db->query(
            "SELECT COUNT(*) AS total FROM " . $this->table . " 
             WHERE kategori_id = :kategori_id"
        );
        $this->db->bind(':kategori_id', $kategori_id);
        $result = $this->db->single();
        return $result->total ?? 0;
    }
}