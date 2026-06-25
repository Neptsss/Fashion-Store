    <?php App\Core\Flasher::flash(); ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-body">
            <p class="hero-text">Koleksi Terbaru Kami</p>
            <h1 class="hero-title">Academic Excellence In Every Stitch</h1>
            <p class="hero-desc">
                Temukan produk berkualitas tinggi yang sempurna untuk kebutuhan akademis dan gaya hidup Anda. Dengan desain modern dan bahan premium, kami siap mendampingi setiap momen berhargamu.
            </p>
            <a href="<?= BASE_URL . '/products'; ?>" class="btn btn-primary">Belanja Sekarang</a>
        </div>
        <div class="hero-image">
            <img src="https://picsum.photos/500/400?random=1" alt="Hero Product">
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container" style="background: linear-gradient(135deg, rgba(6, 82, 214, 0.05) 0%, rgba(175, 201, 255, 0.1) 100%); border-radius: 15px; margin-top: 50px;">
        <h2 class="section-title" style="text-align: center; margin-bottom: 30px;">Jelajahi Kategori</h2>
        <div class="kategori">
            <a href="<?= BASE_URL . '/products?kategori=1'; ?>" class="kategori-badge shirt">
                T-Shirt
            </a>
            <a href="<?= BASE_URL . '/products?kategori=2'; ?>" class="kategori-badge hat">
               Hat
            </a>
            <a href="<?= BASE_URL . '/products?kategori=3'; ?>" class="kategori-badge jeans">
                Jeans
            </a>
        </div>
    </div>

<div class="container" style="padding-top: 60px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <h2 class="section-title product">Produk Terbaru</h2>
            <a href="<?= BASE_URL . '/products'; ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 500; transition: all 0.3s ease;">
                Lihat Semua →
            </a>
        </div>

        <div class="card-container">
            <?php if (!empty($produk)) : ?>
                <?php $count = 0; ?>
                <?php foreach ($produk as $item) : ?>
                    <?php if ($count < 6) : ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="https://picsum.photos/300/300?random=<?= $item['id']; ?>" alt="<?= $item['nama']; ?>">
                            </div>
                            <div class="card-body">
                                <p class="card-title"><?= $item['nama']; ?></p>
                                <p class="card-subtitle">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                                <p cl   ass="card-desc"><?= strlen($item['deskripsi']) > 50 ? substr($item['deskripsi'], 0, 50) . '...' : $item['deskripsi']; ?></p>
                                <hr>
                                <div class="btn-card">
                                    <a href="<?= BASE_URL . '/detail/' . $item['id']; ?>">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        <?php $count++; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p style="text-align: center; color: var(--text-secondary);">Belum ada produk tersedia</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?= BASE_URL . '/products'; ?>" class="btn btn-primary">Lihat Semua Produk</a>
        </div>
    </div>

   