<div class="container profile-page">
    <div class="profile-layout">

        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-photo-container">
                    <?php $profilePhoto = !empty($user['foto'])
                        ? BASE_URL . '/images/users/' . htmlspecialchars($user['foto'], ENT_QUOTES, 'UTF-8')
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama_lengkap']) . '&background=0652d6&color=fff&size=150'; ?>
                    <img src="<?= $profilePhoto; ?>" alt="Foto Profil" class="profile-img">
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $user['username']): ?>
                        <button type="button" id="editPhotoButton" class="btn-edit-photo"><i class="bi bi-camera-fill"></i></button>
                    <?php endif; ?>
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($user['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p class="profile-username">@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></p>

                <hr class="divider">

                <ul class="user-profile-menu">
                    <?php $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                    <li>
                        <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>" class="<?= strpos($currentPath, '/history') === false ? 'active' : ''; ?>"><i class="bi bi-person"></i> Informasi Profil</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'Pembeli'): ?>
                        <li>
                            <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/history"
                                class="<?= strpos($currentPath, '/history') !== false ? 'active' : ''; ?>">
                                <i class="bi bi-bag-check"></i> History Pemesanan
                            </a>
                        </li>
                    <?php else: ?>

                        <li>
                            <a href="<?= BASE_URL ?>/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL; ?>/logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
                </ul>

                <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $user['username']): ?>
                    <div id="photoModal" class="modal-overlay">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Ubah Foto Profil</h3>
                                <button type="button" id="closePhotoModal" class="modal-close"><i class="bi bi-x-lg"></i></button>
                            </div>
                            <form action="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/update" method="post" enctype="multipart/form-data" class="photo-modal-form">
                                <p>Pilih file JPG, JPEG, atau PNG maksimal 2MB.</p>
                                <div class="form-group">
                                    <label for="foto">Foto Profil Baru</label>
                                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png" class="input-control" required>
                                </div>
                                <input type="hidden" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="telp" value="<?= htmlspecialchars($user['telp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="alamat" value="<?= htmlspecialchars($user['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="modal-actions">
                                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
                                    <button type="button" id="cancelPhotoModal" class="btn btn-outline">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-content">
            <div class="content-card">
                <div class="content-header">
                    <h2 class="profile-info">Informasi Profil</h2>
                    <a href="<?= BASE_URL; ?>/profile/<?= urlencode($user['username']); ?>/edit" class="btn btn-outline btn-sm"><i class="bi bi-pencil-square"></i> Edit Profil</a>
                </div>

                <div class="info-grid">
                    <div class="info-group">
                        <label>Nama Lengkap</label>
                        <p class="info-text"><?= htmlspecialchars($user['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>

                    <div class="info-group">
                        <label>Username</label>
                        <p class="info-text"><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    
                    <div class="info-group">
                        <label>Email</label>
                        <p class="info-text"><?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <?php if ($_SESSION['role'] === 'Pembeli'): ?>

                        <div class="info-group">
                            <label>Nomor Telepon</label>
                            <p><?= htmlspecialchars($user['telp'] ?? '-') ?></p>
                        </div>

                        <div class="info-group address-group">
                            <label>Alamat Pengiriman Utama</label>
                            <p><?= htmlspecialchars($user['alamat'] ?? '-') ?></p>
                        </div>
                    <?php else: ?>

                        <div class="info-group">
                            <label>Role</label>
                            <p class="info-text">Penjual</p>
                        </div>


                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>