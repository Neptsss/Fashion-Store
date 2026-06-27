<div class="page-content">
    <div class="page-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Edit Produk</h2>
            <p>Perbarui informasi produk.</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard/products" class="btn-secondary">&larr; Kembali ke Produk</a>
    </div>

    <div class="table-container" style="padding: 30px; max-width: 800px;">
        <form action="<?= BASE_URL; ?>/dashboard/products/update" method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="produk_id" value="<?= $data['produk']['id']; ?>">
            <input type="hidden" name="varian_id" value="<?= $data['produk']['varian_id']; ?>">
            <input type="hidden" name="foto_lama" value="<?= $data['produk']['foto']; ?>">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['form_data']['nama'] ?? $data['produk']['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <?php foreach ($data['kategori'] as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($data['form_data']['kategori_id'] ?? $data['produk']['kategori_id']) == $k['id'] ? 'selected' : '' ?>><?= $k['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($data['form_data']['stok'] ?? $data['produk']['stok']) ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($data['form_data']['harga'] ?? $data['produk']['harga']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Ukuran</label>
                <input type="text" name="ukuran" class="form-control" value="<?= htmlspecialchars($data['form_data']['ukuran'] ?? $data['produk']['ukuran']) ?>">
            </div>
            <div class="form-group">
                <label>Gambar (Kosongkan jika tidak ingin mengubah gambar)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <?php if ($data['produk']['foto']): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?= BASE_URL; ?>/images/products/<?= $data['produk']['foto'] ?>" alt="Gambar Saat Ini" style="height: 100px; border-radius: 8px;">
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5"><?= htmlspecialchars($data['form_data']['deskripsi'] ?? $data['produk']['deskripsi']) ?></textarea>
            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>/dashboard/products" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Perbarui Produk</button>
            </div>
        </form>
    </div>
</div>