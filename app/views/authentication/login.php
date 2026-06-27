<div class="auth-wrapper">
    <?php App\Core\Flasher::flash(); ?>
    <div class="auth-card">
        <div class="auth-header">
            <h2>Stellar & Co.</h2>
            <p>Selamat datang kembali! Silakan masuk ke akun Anda.</p>
        </div>

        <form action="<?= BASE_URL . '/login'; ?>" method="POST">

            <div class="form-group">
                <label for="username">Username <span style="color:red;">*</span> </label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color:red;">*</span></label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password" required>
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Masuk</button>

        </form>

        <div class="auth-footer">
            <p>Belum punya akun? <a href="<?= BASE_URL . '/register'; ?>">Daftar sekarang</a></p>
        </div>
    </div>
</div>
<script src="<?= BASE_URL; ?>/assets/js/auth.js"></script>