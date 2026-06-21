<div class="container checkout-page">
    <div class="bread-crumbs">
        <ul class="bread-crumbs-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="#" class="active">Checkout</a></li>
        </ul>
    </div>

    <form action="proses_checkout.php" method="POST" enctype="multipart/form-data" class="checkout-layout">

        <div class="checkout-form-section">
            <h2 class="section-title">Alamat Pengiriman</h2>

            <div class="address-card">
                <div class="address-header">
                    <p class="user-name"><strong>Nama Pembeli</strong></p>
                    <span class="badge-utama">Utama</span>
                </div>
                <p class="user-phone">081234567890</p>
                <p class="user-address">Jl. Aman jaya sentosa</p>

                <a href="profile.php" class="btn-link">Ubah Alamat di Profil</a>
            </div>

            <h2 class="section-title" style="margin-top: 40px;">Pembayaran</h2>
            <div class="form-card">
                <p class="payment-info">Silakan transfer sebesar <strong>Rp 265.000</strong> ke rekening berikut:<br>
                    <strong>BCA 1234567890 a.n. Stellar Co</strong>
                </p>

                <div class="form-group">
                    <label for="bukti_bayar">Upload Bukti Transfer</label>
                    <input type="file" id="bukti_bayar" name="bukti_bayar" class="form-input file-input" accept="image/jpeg, image/png, image/jpg" required>
                    <small class="form-hint">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</small>
                </div>
            </div>
        </div>

        <div class="checkout-summary-section">
            <div class="summary-card">
                <h3>Ringkasan Pesanan</h3>

                <div class="summary-item">
                    <img src="https://picsum.photos/100/100" alt="Produk" class="summary-img">
                    <div class="summary-detail">
                        <h4>Product name</h4>
                        <p class="summary-var">Ukuran: L | Qty: 1</p>
                        <p class="summary-price">Rp 250.000</p>
                    </div>
                </div>

                <hr class="divider">

                <div class="summary-calc">
                    <div class="calc-row">
                        <span>Subtotal</span>
                        <span>Rp 250.000</span>
                    </div>
                    <div class="calc-row">
                        <span>Ongkos Kirim</span>
                        <span>Rp 15.000</span>
                    </div>
                    <div class="calc-row total-row">
                        <span>Total Pembayaran</span>
                        <span>Rp 265.000</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Selesaikan Pesanan</button>
            </div>
        </div>
    </form>
</div>