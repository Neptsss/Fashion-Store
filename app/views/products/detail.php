<div class="container detail">
    <div class="bread-crumbs">
        <ul class="bread-crumbs-menu">
            <li>
                <a href="<?= BASE_URL; ?>">Home</a>
            </li>
            <li>
                <a href="<?= BASE_URL . '/products'; ?>">Produk</a>
            </li>
            <li>
                <a href="#" class="active"><?= htmlspecialchars($data['produk']['nama'] ?? 'Detail'); ?></a>
            </li>
        </ul>
    </div>

    <div class="detail-row">
        <div class="detail-product">
            <div class="product-image-container">
                <div class="product-image">
                    <img id="mainImage" src="<?= BASE_URL . '/images/products/' . $data['produk']['foto']; ?>" alt="<?= htmlspecialchars($data['produk']['nama'] ?? ''); ?>" class="main-product-image">
                </div>
                <div class="image-badge" id="stockBadge">
                    <span id="stockLabel"><?= ($data['produk']['stok_total'] ?? 0) > 0 ? '✓ Tersedia' : '✗ Habis'; ?></span>
                </div>
            </div>

            <div class="product-desc">
                <h1 class="product-title"><?= htmlspecialchars($data['produk']['nama'] ?? ''); ?></h1>

                <div class="price-section">
                    <p class="product-price" id="productPrice">Rp <?= number_format($data['produk']['harga'] ?? 0, 0, ',', '.'); ?></p>
                    <p class="product-stock" id="productStock">Stok Total: <span id="stockCount"><?= $data['produk']['stok_total'] ?? 0; ?></span> tersedia</p>
                </div>



                <div class="desc-content">
                    <h3>Deskripsi Produk</h3>
                    <p>
                        <?= htmlspecialchars($data['produk']['deskripsi'] ?? ''); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="checkout">
            <div class="checkout-card">
                <h3>Atur Pesanan</h3>

                <form id="checkoutForm" method="POST">
                    <div class="form-group">
                        <label>Pilih Ukuran: <span class="required">*</span></label>
                        <select id="sizeSelect" name="varian_id" class="input-control" onchange="updateVarianInfo()">
                            <option value="">-- Pilih Ukuran --</option>
                            <?php if (!empty($data['varian'])): ?>
                                <?php foreach ($data['varian'] as $v): ?>
                                    <option value="<?= $v['id']; ?>" data-stok="<?= $v['stok']; ?>" data-ukuran="<?= htmlspecialchars($v['ukuran']); ?>">
                                        <?= htmlspecialchars($v['ukuran']); ?> (Stok: <?= $v['stok']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Tidak ada varian tersedia</option>
                            <?php endif; ?>
                        </select>
                        <span class="error-msg" id="sizeError"></span>
                    </div>

                    <div class="form-group">
                        <label>Jumlah: <span class="required">*</span></label>
                        <div class="qty-control">
                            <button type="button" class="btn-qty" id="decreaseBtn" onclick="decreaseQuantity()" title="Kurangi">−</button>
                            <input type="number" id="quantityInput" name="quantity" value="1" class="input-qty" min="1" readonly>
                            <button type="button" class="btn-qty" id="increaseBtn" onclick="increaseQuantity()" title="Tambah">+</button>
                        </div>
                        <span class="error-msg" id="quantityError"></span>
                        <span class="info-text" id="stockWarning"></span>
                    </div>

                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span class="subtotal-price" id="subtotalPrice">Rp <?= number_format($data['produk']['harga'] ?? 0, 0, ',', '.'); ?></span>
                    </div>

                    <div class="checkout-actions">
                        <button type="button" class="btn btn-primary" id="buyNowBtn" onclick="buyNow(event)">
                            Beli Sekarang
                        </button>
                    </div>

                    <div class="success-msg" id="successMessage"></div>
                </form>


            </div>
        </div>
    </div>
</div>

<script>
    const productData = {
        id: <?= $data['produk']['id']; ?>,
        nama: '<?= htmlspecialchars($data['produk']['nama'] ?? ''); ?>',
        harga: <?= $data['produk']['harga'] ?? 0; ?>,
        stok_total: <?= $data['produk']['stok_total'] ?? 0; ?>
    };

    let currentVarianStock = 0;

    // Format Rupiah
    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(num).replace('Rp', 'Rp ');
    }

    function updateVarianInfo() {
        const sizeSelect = document.getElementById('sizeSelect');
        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];

        if (selectedOption.value) {
            currentVarianStock = parseInt(selectedOption.dataset.stok) || 0;
            document.getElementById('quantityInput').max = currentVarianStock;
            document.getElementById('quantityInput').value = 1;
            document.getElementById('sizeError').textContent = '';
            updateSubtotal();

            if (currentVarianStock < 5 && currentVarianStock > 0) {
                document.getElementById('stockWarning').textContent = 'Stok terbatas!';
                document.getElementById('stockWarning').style.color = '#f59e0b';
            } else if (currentVarianStock === 0) {
                document.getElementById('stockWarning').textContent = 'Stok habis';
                document.getElementById('stockWarning').style.color = '#ef4444';
            } else {
                document.getElementById('stockWarning').textContent = '';
            }
        } else {
            currentVarianStock = 0;
        }
    }

    function increaseQuantity() {
        const input = document.getElementById('quantityInput');

        if (parseInt(input.value) < currentVarianStock) {
            input.value = parseInt(input.value) + 1;
            updateSubtotal();
        } else if (currentVarianStock === 0) {
            showError('quantityError', 'Pilih ukuran terlebih dahulu');
        } else {
            showError('quantityError', 'Stok tidak cukup');
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantityInput');

        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            updateSubtotal();
        }
    }

    function updateSubtotal() {
        const quantity = parseInt(document.getElementById('quantityInput').value);
        const price = productData.harga;
        const subtotal = quantity * price;

        document.getElementById('subtotalPrice').textContent = formatRupiah(subtotal);
        document.getElementById('quantityError').textContent = '';
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    function clearErrors() {
        document.getElementById('sizeError').textContent = '';
        document.getElementById('quantityError').textContent = '';
    }

    function validateForm() {
        clearErrors();
        let isValid = true;

        const varianId = document.getElementById('sizeSelect').value;
        if (!varianId) {
            showError('sizeError', 'Silakan pilih ukuran terlebih dahulu');
            isValid = false;
        }

        const quantity = parseInt(document.getElementById('quantityInput').value);
        if (quantity < 1) {
            showError('quantityError', 'Jumlah minimal adalah 1');
            isValid = false;
        }

        if (quantity > currentVarianStock) {
            showError('quantityError', 'Stok tidak cukup');
            isValid = false;
        }

        return isValid;
    }


    function buyNow(event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        const varianId = document.getElementById('sizeSelect').value;
        const quantity = document.getElementById('quantityInput').value;

        const checkoutUrl = `<?= BASE_URL; ?>/checkout?product_id=${productData.id}&varian_id=${varianId}&qty=${quantity}`;
        window.location.href = checkoutUrl;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantityInput');

        quantityInput.addEventListener('input', function() {
            let value = parseInt(this.value) || 1;

            if (value < 1) {
                value = 1;
            }

            if (value > currentVarianStock) {
                value = currentVarianStock || 1;
            }

            this.value = value;
            updateSubtotal();
        });

        const successMsg = document.getElementById('successMessage');
        if (successMsg && successMsg.textContent) {
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 3000);
        }
    });

    document.addEventListener('keydown', function(e) {
        if (document.activeElement.id === 'quantityInput') {
            if (e.key === 'ArrowUp') {
                increaseQuantity();
            } else if (e.key === 'ArrowDown') {
                decreaseQuantity();
            }
        }
    });
</script>

<style>
    .info-text {
        display: block;
        font-size: 0.85rem;
        margin-top: 4px;
        font-weight: 600;
    }
</style>