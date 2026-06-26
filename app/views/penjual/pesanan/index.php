<div class="page-content">
    <div class="page-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Pesanan</h2>
            <p>Kelola pesanan pelanggan Anda.</p>
        </div>
    </div>

    <div class="table-container" style="margin-top: 30px;">
        <table class="custom-table" style="width: 100%;">
            <thead>
                <tr style="width: 100%;">
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 25%;">Pelanggan</th>
                    <th style="width: 20%;">Total Harga</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['pesanan'])): ?>
                    <?php foreach ($data['pesanan'] as $p): ?>
                        <tr>
                            <td>#<?= $p['id'] ?></td>
                            <td><?= date('d M Y H:i', strtotime($p['tgl_pemesanan'])) ?></td>
                            <td>
                                <strong><?= $p['nama_user'] ?></strong>
                            </td>
                            <td class="price">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <?php 
                                    $badgeClass = '';
                                    switch($p['status']) {
                                        case 'Diproses': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'Dalam pengiriman': $badgeClass = 'bg-info text-white'; break;
                                        case 'Selesai': $badgeClass = 'bg-success text-white'; break;
                                        case 'Dibatalkan': $badgeClass = 'bg-danger text-white'; break;
                                    }
                                ?>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; display: inline-block; <?= $badgeClass == 'bg-warning text-dark' ? 'background-color: #fef08a; color: #854d0e;' : ($badgeClass == 'bg-success text-white' ? 'background-color: #bbf7d0; color: #166534;' : ($badgeClass == 'bg-danger text-white' ? 'background-color: #fecaca; color: #991b1b;' : 'background-color: #bae6fd; color: #075985;')) ?>">
                                    <?= $p['status'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= BASE_URL ?>/dashboard/orders/detail/<?= $p['id'] ?>" class="btn-icon" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                    
                                    <button class="btn-icon" title="Ubah Status" onclick="openStatusModal(<?= $p['id'] ?>, '<?= $p['status'] ?>')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">Belum ada pesanan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ubah Status -->
<div id="statusModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Ubah Status Pesanan</h2>
            <button class="close-modal" onclick="closeStatusModal()">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/dashboard/orders/update-status" method="POST">
            <input type="hidden" name="id" id="orderId">
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="orderStatus" class="form-control">
                    <option value="Diproses">Diproses</option>
                    <option value="Dalam pengiriman">Dalam pengiriman</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeStatusModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(id, status) {
    document.getElementById('orderId').value = id;
    document.getElementById('orderStatus').value = status;
    document.getElementById('statusModal').classList.add('show');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('show');
}
</script>
