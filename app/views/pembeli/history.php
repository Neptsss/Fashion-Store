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
                <h3 class="profile-name"><?= $user['nama_lengkap']; ?></h3>
                <p class="profile-username">@<?= $user['username']; ?></p>
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
            <h2 class="section-title" style="margin-bottom: 25px;">History Pemesanan</h2>

            <?php if (empty($pesananUser)): ?>
                <div class="empty-state-card">
                    <p>Anda belum memiliki pesanan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($pesananUser as $pesanan): ?>
                    <?php
                    $statusClass = 'badge-secondary';
                    if ($pesanan['status'] === 'Diproses' || $pesanan['status'] === 'Dalam pengiriman') {
                        $statusClass = 'badge-warning';
                    } elseif ($pesanan['status'] === 'Selesai') {
                        $statusClass = 'badge-success';
                    } elseif ($pesanan['status'] === 'Dibatalkan') {
                        $statusClass = 'badge-danger';
                    }
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-id">
                                <i class="bi bi-bag-fill"></i> Pesanan #<?= $pesanan['id']; ?>
                                <span class="order-date"><?= date('d M Y', strtotime($pesanan['tgl_pemesanan'])); ?></span>
                            </div>
                            <span class="badge <?= $statusClass; ?>"><?= $pesanan['status']; ?></span>
                        </div>

                        <div class="order-body">
                            <div class="order-item">
                                <img src="<?= BASE_URL; ?>/images/products/<?= $pesanan['product_image'] ?? 'default-product.png'; ?>" alt="Produk" class="order-img">
                                <div class="order-detail">
                                    <h4 class="order-title"><?= htmlspecialchars($pesanan['product_name'] ?? 'Produk'); ?></h4>
                                    <p class="order-var">
                                        <?= $pesanan['product_varian'] ? 'Ukuran: ' . htmlspecialchars($pesanan['product_varian']) . ' | ' : ''; ?>
                                        Qty: <?= $pesanan['product_qty']; ?>
                                    </p>
                                </div>
                                <div class="order-price">
                                    <p>Total Belanja</p>
                                    <h4>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="order-footer" style="display: flex; gap: 10px; justify-content: flex-end;">
                            <?php if ($pesanan['status'] === 'Diproses'): ?>
                                <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history/<?= $pesanan['id']; ?>"
                                    class="btn btn-primary btn-sm">
                                    Detail Transaksi
                                </a>

                                <form action="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/order/cancel/<?= $pesanan['id']; ?>"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    <button type="submit" class="btn btn-danger btn-sm" style="border:none;">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history/<?= $pesanan['id']; ?>"
                                    class="btn btn-outline btn-sm">
                                    Detail Transaksi
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>