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
                    <?php if ($_SESSION['role'] === 'Pembeli'): ?>
                        <li>
                            <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history"
                                class="<?= strpos($currentPath, '/history') !== false ? 'active' : ''; ?>">
                                <i class="bi bi-bag-check"></i> History Pemesanan
                            </a>
                        </li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL; ?>/logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
                </ul>
            </div>
        </div>

        <div class="profile-content">
            <div class="content-card">
                <div class="content-header">
                    <h2 class="profile-info">Edit Profil</h2>
                </div>

                <form action="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/update" method="post" class="profile-form">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="input-control" value="<?= htmlspecialchars($user['nama_lengkap']); ?>" required>
                    </div>

                    <?php if ($_SESSION['role'] === 'Pembeli'): ?>

                        <div class="form-group">
                            <label for="telp">Nomor Telepon</label>
                            <input type="text" id="telp" name="telp" class="input-control" value="<?= htmlspecialchars($user['telp'] ?? ''); ?>" placeholder="0812xxxxxxx">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat Pengiriman Utama</label>
                            <textarea id="alamat" name="alamat" class="input-control" rows="4" placeholder="Masukkan alamat pengiriman"><?= htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                        </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>" class="btn btn-outline" style="margin-top:20px; width:100%; display:block;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>