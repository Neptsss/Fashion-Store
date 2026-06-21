<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Stellar & Co.</h2>
            <p>Selamat datang kembali! Silakan masuk ke akun Anda.</p>
        </div>

        <form action="proses_login.php" method="POST">

            <div class="form-group">
                <label for="username">Username </label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Buat password" required>
                    <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Masuk</button>

        </form>

        <div class="auth-footer">
            <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </div>
    </div>
</div>