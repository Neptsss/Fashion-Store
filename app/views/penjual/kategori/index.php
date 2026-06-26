<div class="page-content">
    <div class="page-header">
        <div class="header-product"
            style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div>
                <h2>Kategori</h2>
                <p>Kelola kategori produk Anda.</p>
            </div>
            <div>
                <a href="<?= BASE_URL; ?>/dashboard/categories/add" class="btn-primary"><i class="bi bi-plus-lg"></i>
                    Tambah Kategori</a>
            </div>
        </div>

        <div class="table-container" style="margin-top: 30px;">
            <table class="custom-table" style="width: 100%;">
                <thead>
                    <tr style="width: 100%;">
                        <th style="width: 5%;">No</th>
                        <th style="width: 85%;">Nama Kategori</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($data['kategori'] as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge-category"
                                    style="font-size: 14px; padding: 6px 12px;"><?= $k['nama'] ?></span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= BASE_URL; ?>/dashboard/categories/edit/<?= $k['id'] ?>" class="btn-icon"><i
                                            class="bi bi-pencil"></i></a>
                                    <a href="<?= BASE_URL; ?>/dashboard/categories/delete/<?= $k['id'] ?>"
                                        class="btn-icon delete"
                                        onclick="return confirm('Are you sure you want to delete this category? Make sure no products are using it.')"><i
                                            class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['kategori'])): ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Tidak ada kategori ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>