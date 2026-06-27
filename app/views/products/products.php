<div class="products-container">
    <div class="sidebar-wrap" style="margin-top:70px;">
        <nav class="sidebar">
            <ul class="sidebar-menu">
                <p class="menu-title"> <i class="bi bi-box-seam"></i>Produk</p>
                <li>
                    <a href="<?= BASE_URL . '/products'; ?>" class="menu-link <?= (basename($_SERVER['REQUEST_URI']) === 'products' || $_SERVER['REQUEST_URI'] === BASE_URL . '/products') ? 'active' : ''; ?>">Semua Produk</a>
                </li>

                <p class="menu-title"><i class="bi bi-tags"></i> Kategori</p>
                <?php foreach ($data['kategori'] as $kat): ?>
                    <li>
                        <a href="<?= BASE_URL . '/products?kategori=' . $kat['id']; ?>" class="menu-link <?= (isset($_GET['kategori']) && $_GET['kategori'] == $kat['id']) ? 'active' : ''; ?>">
                            <?= $kat['nama']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                </li>

            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="content-container">
            <h2 class="content-title">Semua Produk</h2>
            <h4 class="content-subtitle">Showing <?= count($data['produk']); ?> produk tersedia</h4>
        </div>

        <div class="container card-container">
            <?php if (!empty($data['produk'])): ?>
                <?php foreach ($data['produk'] as $item) :  ?>
                    <div class="card">
                        <div class="card-image">
                            <img src="<?= BASE_URL . '/images/products/' . $item['foto']; ?>" alt="<?= htmlspecialchars($item['nama']); ?>">
                        </div>
                        <div class="card-body">
                            <p class="card-title"><?= htmlspecialchars($item['nama']); ?></p>
                            <p class="card-subtitle">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                            <p class="card-desc"><?= strlen($item['deskripsi']) > 50 ? substr($item['deskripsi'], 0, 50) . '...' : $item['deskripsi']; ?></p>
                            <hr>
                            <div class="btn-card">
                                <a href="<?= BASE_URL . '/detail/' . $item['id']; ?>">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="bi bi-box empty-icon"></i>
                    <h3>Belum Ada Produk</h3>
                    <p>Saat ini belum ada produk yang tersedia dalam kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>


    </div>
</div>