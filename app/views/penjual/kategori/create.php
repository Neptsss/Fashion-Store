<div class="page-content">
    <div class="page-header"
        style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Tambah Kategori Baru</h2>
            <p>Buat kategori baru untuk produk Anda.</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard/categories" class="btn-secondary">&larr; Kembali</a>
    </div>

    <div class="table-container" style="padding: 30px; max-width: 600px;">
        <form action="<?= BASE_URL; ?>/dashboard/categories/store" method="post">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>/dashboard/categories" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>