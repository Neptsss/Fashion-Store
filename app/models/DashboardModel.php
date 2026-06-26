<?php

class DashboardModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getStats()
    {
        $stats = [
            'total_produk' => 0,
            'total_kategori' => 0,
            'total_pendapatan' => 0,
            'total_terjual' => 0
        ];

        $this->db->query("SELECT COUNT(*) as total FROM produk");
        $res = $this->db->single();
        $stats['total_produk'] = $res['total'];

        $this->db->query("SELECT COUNT(*) as total FROM kategori");
        $res = $this->db->single();
        $stats['total_kategori'] = $res['total'];

        $this->db->query("SELECT SUM(total_harga) as total FROM pesanan WHERE status = 'Selesai'");
        $res = $this->db->single();
        $stats['total_pendapatan'] = $res['total'] ? $res['total'] : 0;

        $this->db->query("SELECT SUM(dp.quantity) as total FROM detail_pesanan dp JOIN pesanan p ON dp.pesanan_id = p.id WHERE p.status = 'Selesai'");
        $res = $this->db->single();
        $stats['total_terjual'] = $res['total'] ? $res['total'] : 0;

        return $stats;
    }

    public function getSalesChart()
    {
        $query = "SELECT MONTH(tgl_pemesanan) as bulan, SUM(total_harga) as total 
                  FROM pesanan 
                  WHERE YEAR(tgl_pemesanan) = YEAR(CURRENT_DATE()) AND status = 'Selesai' 
                  GROUP BY bulan 
                  ORDER BY bulan";
        $this->db->query($query);
        $result = $this->db->resultSet();

        //Data 12 bulan
        $chartData = array_fill(0, 12, 0);
        foreach ($result as $row) {
            $chartData[$row['bulan'] - 1] = (int) $row['total'];
        }

        return $chartData;
    }
}
