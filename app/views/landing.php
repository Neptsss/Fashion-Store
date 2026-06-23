    <?php App\Core\Flasher::flash(); ?>

    <div class="hero">
        <div class="hero-body">
            <p class="hero-text">Lorem ipsum dolor sit amet.</p>
            <h1 class="hero-title">Academic Exellence In Every Stitch</h1>
            <p class="hero-desc">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste recusandae laborum culpa, odit beatae quibusdam incidunt! Molestias debitis harum natus? Officia distinctio alias obcaecati porro minima molestiae, odit mollitia non!
            </p>
            <a href="<?= BASE_URL . '/products'; ?>" class="btn btn-primary">Belanja Sekarang</a>
        </div>
        <div class="hero-image">
            <img src="https://picsum.photos/400">
        </div>
    </div>
    <div class="container">
        <h2 class="section-title">Categories</h2>
        <div class="kategori">
            <a href="#" class="kategori-badge shirt">
                T-Shirt
            </a>
            <a href="#" class="kategori-badge hat">
                Hat
            </a>
            <a href="#" class="kategori-badge jeans">
                Jeans
            </a>
        </div>
    </div>
    <div class="container">
        <h2 class="section-title product">New Product</h2>

        <div class="container card-container">
            <?php foreach ($produk as $item) : ?>
                <div class="card">
                    <div class="card-image">
                        <img src="https://picsum.photos/300">
                    </div>
                    <div class="card-body">
                        <p class="card-title"><?= $item['nama']; ?></p>
                        <p class="card-subtitle">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                        <p class="card-desc"><?= strlen($item['deskripsi']) > 50 ? substr($item['deskripsi'], 0, 50) . '...' : $item['deskripsi']; ?></p>
                        <hr>
                        <div class="btn-card">
                            <a href="<?= BASE_URL . '/' . $item['id']; ?>">Product Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <a href="" class="btn btn-primary">See More</a>
    </div>