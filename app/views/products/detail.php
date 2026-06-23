<div class="container detail">
    <div class="bread-crumbs">
        <ul class="bread-crumbs-menu">
            <li>
                <a href="#">Products</a>
            </li>
            <li>
                <a href="#" class="active">Stellar Oversized T-Shirt</a>
            </li>
        </ul>
    </div>
    <div class="detail-row">
        <div class="detail-product">
            <div class="product-image">
                <img src="https://picsum.photos/600/750" alt="">
            </div>
            <div class="product-desc">

                <h1 class="product-title"><?= htmlspecialchars($produk['nama']); ?></h1>
                <p class="product-price">Rp <?= htmlspecialchars(number_format($produk['harga'], 0, ',', '.')); ?></p>
                <p class="product-stock">Stock : <?= htmlspecialchars($produk['stok']); ?></p>

                <div class="desc-content">
                    <h3>Deskripsi Produk</h3>
                    <p>
                        <?= htmlspecialchars($produk['deskripsi']); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="checkout">
            <div class="checkout-card">
                <h3>Atur Pesanan</h3>

                <div class="form-group">
                    <label>Pilih Ukuran:</label>
                    <select class="input-control">
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah:</label>
                    <div class="qty-control">
                        <button class="btn-qty">-</button>
                        <input type="number" value="1" class="input-qty" min="1">
                        <button class="btn-qty">+</button>
                    </div>
                </div>

                <div class="subtotal">
                    <span>Subtotal</span>
                    <span class="subtotal-price">Rp 250.000</span>
                </div>
                <a href="<?= BASE_URL . '/checkout-product/'.$produk['id'].'/2'; ?>">
                    <button class="btn btn-primary">Langsung Beli</button>
                </a>
            </div>
        </div>
    </div>
</div>