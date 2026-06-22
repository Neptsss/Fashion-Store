<div class="products-container">
    <div class="sidebar-wrap">
        <nav class="sidebar">
            <ul class="sidebar-menu">
                <p class="menu-title">Products</p>
                <li>
                    <a href="" class="menu-link active">All Products</a>
                </li>
                <li>
                    <a href="" class="menu-link">Trending Products</a>
                </li>
                <p class="menu-title">Categories</p>
                <li>
                    <a href="" class="menu-link">T-Shirt</a>
                </li>
                <li>
                    <a href="" class="menu-link">Hat</a>
                </li>
                <li>
                    <a href="" class="menu-link">Jeans</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="content-container">
            <h2 class="content-title">All Product</h2>
            <h4 class="content-subtitle">Showing 1-10 of 20 results</h4>
        </div>

        <div class="container card-container">
            <div class="card">
                <div class="card-image">
                    <img src="https://picsum.photos/300">
                </div>
                <div class="card-body">
                    <p class="card-title">Product Name</p>
                    <p class="card-subtitle">Rp 3.000.000</p>
                    <p class="card-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit,
                        voluptatem!!</p>
                    <hr>
                    <div class="btn-card">
                        <a href="<?= BASE_URL . '/detail/1'; ?>">Product Detail</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="https://picsum.photos/300">
                </div>
                <div class="card-body">
                    <p class="card-title">Product Name</p>
                    <p class="card-subtitle">Rp 3.000.000</p>
                    <p class="card-desc">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Officia, iure.</p>
                    <hr>
                    <div class="btn-card">
                        <a href="">Product Detail</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="https://picsum.photos/300">
                </div>
                <div class="card-body">
                    <p class="card-title">Product Name</p>
                    <p class="card-subtitle">Rp 3.000.000</p>
                    <p class="card-desc">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, enim?!</p>
                    <hr>
                    <div class="btn-card">
                        <a href="">Product Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- <?php if (empty($data['produk'])): ?>
        <?php if (isset($_GET['keyword']) && $_GET['keyword'] != ''): ?>
        <div class="empty-state">
            <i class="bi bi-search empty-icon"></i>
            <h3>Oops! Produk tidak ditemukan</h3>
            <p>
                Kami tidak dapat menemukan produk yang sesuai dengan pencarian
                "<b><?= htmlspecialchars($_GET['keyword']) ?></b>".
            </p>
            <a href="<?= BASE_URL ?>/product" class="btn-empty">
                Kembali
            </a>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-box empty-icon"></i>
            <h3>Belum Ada Produk</h3>
            <p>
                Saat ini belum ada produk yang tersedia.
            </p>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="container card-container">
            <?php foreach ($data['produk'] as $produk): ?>
            <div class="card">
                <div class="card-image">
                    <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($produk['foto']) ?>"
                        alt="<?= htmlspecialchars($produk['nama']) ?>" onerror="this.src='https://picsum.photos/300'">
                </div>
                <div class="card-body">
                    <p class="card-title"><?= htmlspecialchars($produk['nama']) ?></p>
                    <p class="card-subtitle">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                    <p class="card-desc"><?= htmlspecialchars(substr($produk['deskripsi'], 0, 80)) ?>...</p>
                    <hr>
                    <div class="btn-card">
                        <a href="<?= BASE_URL . '/produk/detail/' . $produk['id']; ?>">Product Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?> -->
        <div class="pagination">
            <ul class="pagi-item">
                <li>
                    <a href="" class="pagi-back">&lt;</a>
                </li>
                <li>
                    <a href="" class="pagi-link">1</a>
                </li>
                <li>
                    <a href="" class="pagi-link">2</a>
                </li>
                <li>
                    <a href="" class="pagi-link">3</a>
                </li>
                <li>
                    <a href="" class="pagi-next">&gt;</a>
                </li>
            </ul>
        </div>
    </div>
</div>