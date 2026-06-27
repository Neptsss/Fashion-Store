<div class="container profile-page">
    <div class="profile-layout">

        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-photo-container">
                    <?php $profilePhoto = !empty($user['foto'])
                        ? BASE_URL . '/images/users/' . htmlspecialchars($user['foto'], ENT_QUOTES, 'UTF-8')
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama_lengkap']) . '&background=0652d6&color=fff&size=150'; ?>
                    <img src="<?= $profilePhoto; ?>" alt="Foto Profil" class="profile-img">
                    
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($user['nama_lengkap']); ?></h3>
                <p class="profile-username">@<?= htmlspecialchars($user['username']); ?></p>
                <hr class="divider">
                <?php $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                <ul class="user-profile-menu">
                    <li><a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>" class="<?= strpos($currentPath, '/history') === false ? 'active' : ''; ?>"><i class="bi bi-person"></i> Informasi Profil</a></li>
                    <li><a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history" class="<?= strpos($currentPath, '/history') !== false ? 'active' : ''; ?>"><i class="bi bi-bag-check"></i> History Pemesanan</a></li>
                    <li><a href="<?= BASE_URL; ?>/logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
                </ul>
            </div>
        </div>

        <div class="profile-content">

            <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Transaksi</a>

            <div class="transaction-card">
                <div class="transaction-header">
                    <h2 class="section-title" style="margin-bottom: 5px;">Detail Transaksi</h2>
                    <div class="transaction-status">
                        <?php
                        $statusClass = 'badge-secondary';
                        if ($pesanan['status'] === 'Diproses') {
                            $statusClass = 'badge-warning';
                        } elseif ($pesanan['status'] === 'Selesai') {
                            $statusClass = 'badge-success';
                        } elseif ($pesanan['status'] === 'Dibatalkan') {
                            $statusClass = 'badge-danger';
                        }
                        ?>
                        <span class="badge <?= $statusClass; ?>"><?= htmlspecialchars($pesanan['status']); ?></span>
                    </div>
                </div>

                <div class="transaction-section">
                    <h3 class="transaction-subtitle">Informasi Pembeli</h3>
                    <div class="detail-grid">
                        <span class="detail-label">Nama</span>
                        <span class="detail-value"><?= htmlspecialchars($pesanan['nama_lengkap'] ?? $user['nama_lengkap']); ?></span>
                    </div>
                    <div class="detail-grid">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?= htmlspecialchars($pesanan['email'] ?? '-'); ?></span>
                    </div>
                    <div class="detail-grid">
                        <span class="detail-label">Nomor Telepon</span>
                        <span class="detail-value"><?= htmlspecialchars($pesanan['nomor_telepon'] ?? '-'); ?></span>
                    </div>
                    <div class="detail-grid">
                        <span class="detail-label">Tanggal Pesanan</span>
                        <span class="detail-value"><?= date('d M Y H:i', strtotime($pesanan['tgl_pemesanan'])); ?></span>
                    </div>
                </div>

                <hr class="divider">

                <div class="transaction-section">
                    <h3 class="transaction-subtitle">Rincian Produk</h3>

                    <?php foreach ($details as $item): ?>
                        <div class="order-item">
                            <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($item['foto'] ?? 'default-product.png'); ?>" alt="Produk" class="order-img">
                            <div class="order-detail">
                                <h4 class="order-title"><?= htmlspecialchars($item['nama']); ?></h4>
                                <?php if (!empty($item['ukuran'])): ?>
                                    <p class="order-var">Ukuran: <?= htmlspecialchars($item['ukuran']); ?></p>
                                <?php endif; ?>
                                <p class="order-var"><?= htmlspecialchars($item['quantity']); ?> x Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="order-price">
                                <p>Total Harga</p>
                                <h4>Rp <?= number_format($item['sub_total'], 0, ',', '.'); ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="divider">

                <div class="transaction-section">
                    <h3 class="transaction-subtitle">Rincian Pembayaran</h3>
                    <div class="calc-row">
                        <span>Total Harga Produk</span>
                        <span>Rp <?= number_format($productTotal, 0, ',', '.'); ?></span>
                    </div>
                    <div class="calc-row">
                        <span>Total Ongkos Kirim</span>
                        <span>Rp <?= number_format($shipping, 0, ',', '.'); ?></span>
                    </div>
                    <div class="calc-row total-row">
                        <span>Total Belanja</span>
                        <span>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="transaction-footer">
                    <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history" class="btn btn-primary">Kembali ke History</a>
                </div>

            </div>
        </div>
    </div>
</div>