<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 500px;">
        <div class="auth-header">
            <h2>Buat Akun Baru</h2>
            <p>Bergabunglah dengan Stellar & Co. untuk pengalaman belanja terbaik.</p>
        </div>

        <form action="proses_register.php" method="POST">

            <div class="form-row">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" placeholder="Masukkan nama" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="Buat username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email aktif" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Buat password" required>
                        <i class="bi bi-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Ulangi password" required>
                        <i class="bi bi-eye toggle-password" id="toggleConfirmPassword"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Daftar Sekarang</button>

        </form>

        <div class="auth-footer">
            <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
        </div>

    </div>
</div>