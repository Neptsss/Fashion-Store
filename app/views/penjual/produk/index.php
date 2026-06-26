<div class="page-content">
    <div class="page-header">
        <div class="header-product"
            style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div>
                <h2>Inventaris Produk</h2>
                <p>Kelola dan pantau koleksi produk Anda.</p>
            </div>
            <div>
                <a href="<?= BASE_URL; ?>/dashboard/products/add" class="btn-primary"><i class="bi bi-plus-lg"></i>
                    Tambah Produk</a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Produk</h3>
            <div class="value"><?= count($data['produk']) ?></div>
        </div>
        <div class="stat-card">
            <h3>Stok Aktif</h3>
            <div class="value blue">85%</div>
        </div>
        <div class="stat-card">
            <h3>Peringatan Stok Rendah</h3>
            <div class="value red">12</div>
        </div>
        <div class="stat-card">
            <h3>Baru Bulan Ini</h3>
            <div class="value">48</div>
        </div>
    </div>

    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['produk'] as $p): ?>
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="<?= BASE_URL; ?>/images/<?= $p['foto'] ?>" alt="<?= $p['nama'] ?>"
                                    class="product-img" onerror="this.src=''">
                                <div class="product-info">
                                    <h4><?= $p['nama'] ?></h4>
                                    <p>ID: LX-<?= sprintf('%05d', $p['id']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $kat_nama = 'N/A';
                            foreach ($data['kategori'] as $k) {
                                if ($k['id'] == $p['kategori_id']) {
                                    $kat_nama = $k['nama'];
                                    break;
                                }
                            }
                            ?>
                            <span class="badge-category"><?= $kat_nama ?></span>
                        </td>
                        <td>
                            <?= !empty($p['stok']) && $p['stok'] > 0 ? $p['stok'] : 'Habis'; ?>
                        </td>
                        <td>
                            <span class="price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL; ?>/dashboard/products/edit/<?= $p['id'] ?>" class="btn-icon"><i
                                        class="bi bi-pencil"></i></a>
                                <a href="<?= BASE_URL; ?>/dashboard/products/delete/<?= $p['id'] ?>" class="btn-icon delete"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i
                                        class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['produk'])): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan <?= count($data['produk']) ?> hasil
            </div>
            <div class="pagination-controls">
                <a href="#" class="page-btn"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div iv>