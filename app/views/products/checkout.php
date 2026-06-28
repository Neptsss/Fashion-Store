<div class="container checkout-page">
    <div class="bread-crumbs">
        <ul class="bread-crumbs-menu">
            <li><a href="<?= BASE_URL; ?>">Home</a></li>
            <li><a href="<?= BASE_URL . '/products'; ?>">Produk</a></li>
            <li><a href="#" class="active">Checkout</a></li>
        </ul>
    </div>

    <form action="<?= BASE_URL . '/proses-checkout'; ?>" method="POST" enctype="multipart/form-data" class="checkout-layout" id="checkoutForm">
        <div class="checkout-form-section">
            <h2 class="section-title">Alamat Pengiriman</h2>

            <div class="address-card">
                <div class="address-header">
                    <p class="user-name"><strong><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'User'); ?></strong></p>
                    <span class="badge-utama">Utama</span>
                </div>
                <p class="user-phone"><?= htmlspecialchars($_SESSION['telp'] ?? 'Belum diisi'); ?></p>
                <p class="user-address"><?= htmlspecialchars($_SESSION['alamat'] ?? 'Belum diisi'); ?></p>

                <a href="<?= BASE_URL . '/profile/' . urlencode($_SESSION['username']); ?>/edit" class="btn-link">Ubah Alamat di Profil</a>
            </div>

            <div class="checkout-items-card">
                <div class="checkout-items-header">
                    <button type="button" class="btn btn-secondary" id="openProductModalBtn">Tambah Produk</button>
                </div>

                <div class="checkout-items-cards-wrap" id="itemsCardsContainer">
                    <div class="empty-card">
                        <p>Belum ada produk. Klik "Tambah Produk" untuk memilih item.</p>
                    </div>
                </div>

                <div class="checkout-note">Pilih produk, varian dan jumlah. Sistem akan menghitung total secara otomatis.</div>
            </div>

            <h2 class="section-title" style="margin-top: 40px;">Pembayaran</h2>
            <div class="form-card">
                <div class="payment-info">
                    <strong>Informasi Pembayaran:</strong><br>
                    Silakan transfer sebesar <strong id="totalBayar">Rp 0</strong> ke rekening berikut:<br>
                    <strong>BCA 1234567890</strong><br>
                    <strong>a.n. Stellar & Co.</strong>
                </div>

                <div class="form-group">
                    <label for="bukti_bayar">Upload Bukti Transfer: <span class="required">*</span></label>
                    <input type="file" id="bukti_bayar" name="bukti_bayar" class="form-input file-input" accept="image/jpeg,image/png,image/jpg" required>
                    <small class="form-hint">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    <span class="error-msg" id="buktiError"></span>
                </div>
            </div>
        </div>

        <div class="checkout-summary-section">
            <div class="summary-card">
                <h3>Ringkasan Pesanan</h3>
                <div id="summaryList" class="summary-list">
                    <p class="summary-empty">Pilih produk untuk melihat ringkasan.</p>
                </div>

                <hr class="divider">

                <div class="summary-calc">
                    <div class="calc-row">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">Rp 0</span>
                    </div>
                    <div class="calc-row">
                        <span>Ongkos Kirim</span>
                        <span id="ongkirDisplay">Rp 15.000</span>
                    </div>
                    <div class="calc-row total-row">
                        <span>Total Pembayaran</span>
                        <span id="totalDisplay">Rp 0</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">✓ Bayar Sekarang</button>
            </div>
        </div>

        <div id="formItemsContainer"></div>
    </form>

    <div id="modalOverlay" class="modal-overlay hidden">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Pilih Produk</h3>
                <button type="button" class="modal-close" id="closeProductModalBtn">×</button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <input type="text" id="searchProductInput" class="input-control" placeholder="Cari produk atau varian...">
                </div>
                <div class="product-list" id="productList"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const availableItems = <?= json_encode($data['productVariants'] ?? []); ?>;
    const prefillItem = <?= json_encode($data['prefillItem'] ?? null); ?>;
    const ONGKIR = 15000;
    let selectedItems = [];

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(num).replace('Rp', 'Rp ');
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderProductList(filter = '') {
        const list = document.getElementById('productList');
        const query = filter.trim().toLowerCase();
        list.innerHTML = '';

        const filtered = availableItems.filter(item => {
            const name = (item.nama || '').toLowerCase();
            const ukuran = (item.ukuran || '').toLowerCase();
            const foto = (item.foto || '').toLowerCase();
            return name.includes(query) || ukuran.includes(query) || foto.includes(query);
        });

        if (filtered.length === 0) {
            list.innerHTML = '<p class="empty-list">Tidak ditemukan produk.</p>';
            return;
        }

        filtered.forEach(item => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
                <div class="product-card-preview">
                    <img src="<?= BASE_URL; ?>/images/products/${escapeHtml(item.foto || 'https://picsum.photos/100/100')}" alt="${escapeHtml(item.nama)}">
                </div>
                <div class="product-card-left">
                    <div class="product-card-title">${escapeHtml(item.nama)}</div>
                    <div class="product-card-meta">Varian: ${escapeHtml(item.ukuran)} | Stok: ${item.stok}</div>
                    <div class="product-card-price">${formatRupiah(item.harga)}</div>
                </div>
                <button type="button" class="btn btn-secondary btn-add-item" data-varian-id="${item.varian_id}">Tambah</button>
            `;
            list.appendChild(productCard);
        });
    }

    function openProductModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        renderProductList();
        document.getElementById('searchProductInput').value = '';
    }

    function closeProductModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
    }

    function addOrIncreaseItem(itemData) {
        const existing = selectedItems.find(i => String(i.varian_id) === String(itemData.varian_id));
        if (existing) {
            if (existing.quantity < existing.stok) {
                existing.quantity += 1;
            }
        } else {
            selectedItems.push({
                produk_id: itemData.produk_id || itemData.produk_id,
                varian_id: itemData.varian_id,
                nama: itemData.nama,
                ukuran: itemData.ukuran,
                harga: Number(itemData.harga),
                stok: Number(itemData.stok),
                quantity: 1,
                foto: itemData.foto
            });
        }
        renderSelectedItems();
    }

    function renderSelectedItems() {
        const cardsContainer = document.getElementById('itemsCardsContainer');
        const summaryList = document.getElementById('summaryList');
        const container = document.getElementById('formItemsContainer');
        cardsContainer.innerHTML = '';
        container.innerHTML = '';

        if (selectedItems.length === 0) {
            cardsContainer.innerHTML = '<div class="empty-card"><p>Belum ada produk. Klik "Tambah Produk" untuk memilih item.</p></div>';
            summaryList.innerHTML = '<p class="summary-empty">Pilih produk untuk melihat ringkasan.</p>';
            updateTotals();
            return;
        }

        selectedItems.forEach((item, index) => {
            const subtotal = item.harga * item.quantity;
            const card = document.createElement('div');
            card.className = 'item-card';
            card.innerHTML = `
                <div class="item-card-image">
                    <img src="<?= BASE_URL; ?>/images/products/${escapeHtml(item.foto)}" alt="${escapeHtml(item.nama)}">
                </div>
                <div class="item-card-body">
                    <div class="item-card-title">${escapeHtml(item.nama)}</div>
                    <div class="item-card-variant">Varian: ${escapeHtml(item.ukuran)}</div>
                    <div class="item-card-price">${formatRupiah(item.harga)}</div>
                    <div class="item-card-qty">
                        <button type="button" class="btn-qty" onclick="changeItemQty(${index}, -1)">−</button>
                        <span>${item.quantity}</span>
                        <button type="button" class="btn-qty" onclick="changeItemQty(${index}, 1)">+</button>
                    </div>
                    <div class="item-card-subtotal">Subtotal: ${formatRupiah(subtotal)}</div>
                </div>
                <button type="button" class="btn btn-danger btn-remove btn-card-remove" onclick="removeItem(${index})">Hapus</button>
            `;
            cardsContainer.appendChild(card);

            container.innerHTML += `
                <input type="hidden" name="product_id[]" value="${item.produk_id}">
                <input type="hidden" name="varian_id[]" value="${item.varian_id}">
                <input type="hidden" name="quantity[]" value="${item.quantity}" data-index="${index}">
            `;
        });

        summaryList.innerHTML = selectedItems.map(item => `
            <div class="summary-item-row">
                <span>${escapeHtml(item.nama)} (${escapeHtml(item.ukuran)}) x ${item.quantity}</span>
                <span>${formatRupiah(item.harga * item.quantity)}</span>
            </div>
        `).join('');

        updateTotals();
    }

    function changeItemQty(index, delta) {
        const item = selectedItems[index];
        if (!item) return;
        const newQty = item.quantity + delta;
        if (newQty < 1 || newQty > item.stok) {
            return;
        }
        item.quantity = newQty;
        renderSelectedItems();
    }

    function removeItem(index) {
        selectedItems.splice(index, 1);
        renderSelectedItems();
    }

    function updateTotals() {
        const subtotal = selectedItems.reduce((sum, item) => sum + item.harga * item.quantity, 0);
        const total = subtotal + ONGKIR;
        document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        document.getElementById('ongkirDisplay').textContent = formatRupiah(ONGKIR);
        document.getElementById('totalDisplay').textContent = formatRupiah(total);
        document.getElementById('totalBayar').textContent = formatRupiah(total);
    }

    function validateCheckoutForm() {
        const buktiInput = document.getElementById('bukti_bayar');
        const errorElem = document.getElementById('buktiError');
        errorElem.textContent = '';

        if (selectedItems.length === 0) {
            alert('Silakan tambahkan minimal satu produk ke keranjang.');
            return false;
        }

        if (!buktiInput.files || buktiInput.files.length === 0) {
            errorElem.textContent = 'Silakan upload bukti pembayaran';
            return false;
        }

        const file = buktiInput.files[0];
        const validExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validExtensions.includes(file.type)) {
            errorElem.textContent = 'Format file harus JPG, JPEG, atau PNG';
            return false;
        }

        if (file.size > 2000000) {
            errorElem.textContent = 'Ukuran file maksimal 2MB';
            return false;
        }

        return true;
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        if (!validateCheckoutForm()) {
            e.preventDefault();
        }
    });

    document.getElementById('openProductModalBtn').addEventListener('click', openProductModal);
    document.getElementById('closeProductModalBtn').addEventListener('click', closeProductModal);
    document.getElementById('searchProductInput').addEventListener('input', function() {
        renderProductList(this.value);
    });
    document.getElementById('modalOverlay').addEventListener('click', function(event) {
        if (event.target === this) {
            closeProductModal();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.matches('.btn-add-item')) {
            const varianId = event.target.dataset.varianId;
            const item = availableItems.find(i => String(i.varian_id) === String(varianId));
            if (item) {
                addOrIncreaseItem(item);
                closeProductModal();
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (prefillItem && prefillItem.varian_id) {
            addOrIncreaseItem(prefillItem);
        }
        renderSelectedItems();
    });
</script>

<style>
    .checkout-layout {
        display: grid;
        grid-template-columns: 1.65fr 1fr;
        gap: 24px;
    }

    .checkout-form-section,
    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
    }

    .address-card,
    .form-card,
    .checkout-items-card {
        background: #f8fafc;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .checkout-items-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .checkout-items-cards-wrap {
        display: grid;
        gap: 18px;
    }

    .item-card {
        display: grid;
        grid-template-columns: 140px 1fr auto;
        gap: 18px;
        align-items: center;
        padding: 18px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
    }

    .item-card-image {
        width: 140px;
        height: 140px;
        overflow: hidden;
        border-radius: 18px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-card-body {
        display: grid;
        gap: 10px;
    }

    .item-card-title {
        font-weight: 700;
        color: #111827;
    }

    .item-card-variant,
    .item-card-price,
    .item-card-subtotal {
        color: #6b7280;
    }

    .item-card-qty {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-card-remove {
        height: fit-content;
        justify-self: end;
    }

    .empty-card {
        padding: 24px;
        text-align: center;
        border: 1px dashed #d1d5db;
        border-radius: 18px;
        color: #6b7280;
        background: #f8fafc;
    }

    .qty-control-small {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .input-qty-small {
        width: 44px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 6px;
        background: #fff;
    }

    .btn-qty,
    .btn-remove,
    .btn-secondary,
    .btn-primary {
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        cursor: pointer;
    }

    .btn-qty {
        background: #f3f4f6;
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
        width: 100%;
        margin-top: 16px;
    }

    .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .summary-item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #374151;
    }

    .summary-empty,
    .empty-list {
        color: #6b7280;
        padding: 18px;
        text-align: center;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: grid;
        place-items: center;
        z-index: 50;
        padding: 24px;
    }

    .modal-overlay.hidden {
        display: none;
    }

    .modal-card {
        width: min(100%, 860px);
        margin-top: 130px;
        max-height: 70vh;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 80px rgba(15, 23, 42, 0.16);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 28px;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-search {
        margin-bottom: 16px;
    }

    .product-card {
        display: grid;
        grid-template-columns: 84px 1fr auto;
        align-items: center;
        gap: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 12px;
    }

    .product-card-preview {
        width: 84px;
        height: 84px;
        overflow: hidden;
        border-radius: 16px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-card-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card-left {
        display: grid;
        gap: 8px;
    }

    .product-card-title {
        font-weight: 700;
        color: #111827;
    }

    .product-card-meta,
    .product-card-price {
        color: #6b7280;
    }

    .product-list {
        max-height: 420px;
        overflow-y: auto;
    }

    .checkout-note {
        margin-top: 14px;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .item-product-name {
        font-weight: 600;
        color: #111827;
    }

    .stock-note {
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 8px;
    }