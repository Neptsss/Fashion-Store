<div class="page-content">
    <div class="page-header"
        style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Tambah Produk Baru</h2>
            <p>Buat produk baru di inventaris Anda.</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard/products" class="btn-secondary">&larr; Kembali ke Produk</a>
    </div>

    <div class="table-container" style="padding: 30px; max-width: 800px;">
        <form action="<?= BASE_URL; ?>/dashboard/products/store" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['form_data']['nama'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <?php foreach ($data['kategori'] as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($data['form_data']['kategori_id'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= $k['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($data['form_data']['stok'] ?? '') ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($data['form_data']['harga'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Ukuran</label>
                <select name="ukuran" class="form-control" required>
                    <option value="" disabled <?= empty($data['form_data']['ukuran'] ?? '') ? 'selected' : '' ?>>Pilih Ukuran</option>
                    <option value="S" <?= ($data['form_data']['ukuran'] ?? '') === 'S' ? 'selected' : '' ?>>Small</option>
                    <option value="M" <?= ($data['form_data']['ukuran'] ?? '') === 'M' ? 'selected' : '' ?>>Medium</option>
                    <option value="L" <?= ($data['form_data']['ukuran'] ?? '') === 'L' ? 'selected' : '' ?>>Large</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gambar</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5"><?= htmlspecialchars($data['form_data']['deskripsi'] ?? '') ?></textarea>
            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>/dashboard/products" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>