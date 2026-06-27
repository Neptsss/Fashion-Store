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
                    <input type="file" id="bukti_bayar" name="bukti_bayar" class="form-input file-input" 
                           accept="image/jpeg,image/png,image/jpg" required>
                    <small class="form-hint">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    <span class="error-msg" id="buktiError"></span>
                </div>
            </div>
        </div>

        <div class="checkout-summary-section">
            <div class="summary-card">
                <h3>Ringkasan Pesanan</h3>

                <!-- Product Summary Item -->
                <div class="summary-item">
                    <img id="produkFoto" src="https://picsum.photos/100/100" alt="Produk" class="summary-img">
                    <div class="summary-detail">
                        <h4 id="produkNama">Produk</h4>
                        <p class="summary-var" id="varianInfo">Ukuran: - | Qty: -</p>
                        <p class="summary-price" id="hargaItem">Rp 0</p>
                    </div>
                </div>

                <hr class="divider">

                <!-- Calculation Summary -->
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

                <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                    ✓ Selesaikan Pesanan
                </button>
            </div>
        </div>

        <input type="hidden" name="produk_id" id="produkId" value="<?= $data['produk']['id'] ?? ''; ?>">
        <input type="hidden" name="varian_id" id="varianId" value="">
        <input type="hidden" name="quantity" id="quantity" value="">
        <input type="hidden" name="harga" id="hargaInput" value="">
    </form>
</div>

<script>
    const checkoutData = {
        produk: {
            id: <?= $data['produk']['id'] ?? 0; ?>,
            nama: '<?= htmlspecialchars($data['produk']['nama'] ?? 'Produk'); ?>',
            harga: <?= $data['produk']['harga'] ?? 0; ?>,
            foto: '<?= $data['produk']['foto'] ?? 'https://picsum.photos/100/100'; ?>'
        },
        qty: <?= $data['qty'] ?? 1; ?>,
        varianId: '<?= $data['varian']['id'] ?? ''; ?>',
        ukuran: '<?= htmlspecialchars($data['varian']['ukuran'] ?? $data['ukuran'] ?? '-'); ?>'
    };

    const ONGKIR = 15000;

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(num).replace('Rp', 'Rp ');
    }

    function initDisplay() {
        document.getElementById('produkNama').textContent = checkoutData.produk.nama;
        document.getElementById('produkFoto').src = 'https://picsum.photos/100/100?random=' + checkoutData.produk.id;
        document.getElementById('varianInfo').textContent = 
            'Ukuran: ' + checkoutData.ukuran + ' | Qty: ' + checkoutData.qty;
        document.getElementById('hargaItem').textContent = formatRupiah(checkoutData.produk.harga);

        document.getElementById('varianId').value = checkoutData.varianId;
        document.getElementById('quantity').value = checkoutData.qty;
        document.getElementById('hargaInput').value = checkoutData.produk.harga;

        const subtotal = checkoutData.produk.harga * checkoutData.qty;
        const total = subtotal + ONGKIR;

        document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        document.getElementById('ongkirDisplay').textContent = formatRupiah(ONGKIR);
        document.getElementById('totalDisplay').textContent = formatRupiah(total);
        document.getElementById('totalBayar').textContent = formatRupiah(total);
    }

    function validateCheckoutForm() {
        const buktiInput = document.getElementById('bukti_bayar');
        
        if (!buktiInput.files || buktiInput.files.length === 0) {
            document.getElementById('buktiError').textContent = 'Silakan upload bukti pembayaran';
            return false;
        }

        const file = buktiInput.files[0];
        const validExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!validExtensions.includes(file.type)) {
            document.getElementById('buktiError').textContent = 'Format file harus JPG, JPEG, atau PNG';
            return false;
        }

        if (file.size > 2000000) {
            document.getElementById('buktiError').textContent = 'Ukuran file maksimal 2MB';
            return false;
        }

        document.getElementById('buktiError').textContent = '';
        return true;
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateCheckoutForm()) {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').textContent = '⏳ Memproses...';
            
            this.submit();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        initDisplay();
    });
</script>

<style>
    .error-msg {
        display: block;
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 6px;
        font-weight: 600;
    }

    #submitBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>