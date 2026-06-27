<div class="page-content">
    <div class="page-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Detail Pesanan #<?= $data['pesanan']['id'] ?></h2>
            <p>Informasi lengkap mengenai pesanan.</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard/orders" class="btn-secondary">&larr; Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <div class="table-container" style="padding: 24px;">
            <h3 style="margin-bottom: 20px; font-size: 16px; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Produk Dipesan</h3>
            <table class="custom-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['detail_pesanan'] as $dp): ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <?php if($dp['foto']): ?>
                                        <img src="<?= BASE_URL ?>/images/products/<?= $dp['foto'] ?>" alt="<?= $dp['nama_produk'] ?>" class="product-img">
                                    <?php else: ?>
                                        <div class="product-img" style="display: flex; align-items: center; justify-content: center; background: #eee;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-info">
                                        <h4><?= $dp['nama_produk'] ?></h4>
                                    </div>
                                </div>
                            </td>
                            <td>Rp <?= number_format($dp['harga_satuan'], 0, ',', '.') ?></td>
                            <td><?= $dp['quantity'] ?></td>
                            <td style="text-align: right; font-weight: 600;">Rp <?= number_format($dp['sub_total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="table-container" style="padding: 24px;">
                <h3 style="margin-bottom: 20px; font-size: 16px; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Ringkasan Pesanan</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                    <span style="color: var(--text-muted);">Tanggal:</span>
                    <span><?= date('d F Y H:i', strtotime($data['pesanan']['tgl_pemesanan'])) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                    <span style="color: var(--text-muted);">Status:</span>
                    <span style="font-weight: 600;"><?= $data['pesanan']['status'] ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 1px dashed var(--border-color); font-size: 16px; font-weight: 700;">
                    <span>Total Keseluruhan:</span>
                    <span style="color: var(--primary-color);">Rp <?= number_format($data['pesanan']['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="table-container" style="padding: 24px;">
                <h3 style="margin-bottom: 20px; font-size: 16px; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Informasi Pelanggan</h3>
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 600;">
                        <?= strtoupper(substr($data['pesanan']['nama_user'], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; margin: 0;"><?= $data['pesanan']['nama_user'] ?></h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">User ID: <?= $data['pesanan']['user_id'] ?></p>
                    </div>
                </div>
            </div>

            <div class="table-container" style="padding: 24px;">
                <h3 style="margin-bottom: 20px; font-size: 16px; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Bukti Pembayaran</h3>
                <?php if($data['pesanan']['bukti_pembayaran']): ?>
                    <a href="<?= BASE_URL ?>/images/payments/<?= $data['pesanan']['bukti_pembayaran'] ?>" target="_blank" style="display: block; text-align: center; background: #f8fafc; padding: 20px; border-radius: 8px; text-decoration: none; border: 1px dashed var(--border-color);">
                        <i class="bi bi-file-earmark-image" style="font-size: 32px; color: var(--text-muted);"></i>
                        <p style="margin-top: 10px; font-size: 14px; color: var(--primary-color);">Lihat Bukti Pembayaran</p>
                    </a>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 14px;">
                        Belum ada bukti pembayaran.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
