<div class="page-content">
    <div class="page-header"
        style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Edit Kategori</h2>
            <p>Perbarui informasi kategori.</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard/categories" class="btn-secondary">&larr; Kembali</a>
    </div>

    <div class="table-container" style="padding: 30px; max-width: 600px;">
        <form action="<?= BASE_URL; ?>/dashboard/categories/update" method="post">
            <input type="hidden" name="id" value="<?= $data['kategori']['id'] ?>">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" value="<?= $data['kategori']['nama'] ?>" required>
            </div>
            <div class="modal-footer" style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>/dashboard/categories" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Ubah Kategori</button>
            </div>
        </form>
    </div>
</div>