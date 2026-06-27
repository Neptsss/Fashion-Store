<?php
$current_url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

$url_segments = explode('/', $current_url);
$page = $url_segments[0];
?>
<nav class="nav-wrap">
    <div class="navbar">
        <div class="nav-container">
            <a href="#" class="nav-title">Stellar & Co.</a>
            <ul class="nav-menu">
                <li>
                    <a href="<?= BASE_URL; ?>" class="<?= ($page === '') ? 'active' : ''; ?>">Home</a>
                </li>
                <li>
                    <a href="<?= BASE_URL . '/products'; ?>"
                        class="<?= ($page === 'products') ? 'active' : ''; ?>">Products</a>
                </li>

            </ul>
        </div>
        <div class="nav-container">
            <form action="<?= BASE_URL; ?>/products" method="get">
                <div class="input-icon">
                    <input type="text" name="keyword"
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Search Collection" class="input-control">
                </div>
            </form>
            <div class="nav-menu">
                <?php
                $username = $_SESSION['nama_lengkap'] ?? 'User';
                $profilePhoto = !empty($_SESSION['foto'])
                    ? BASE_URL . '/images/users/' . htmlspecialchars($_SESSION['foto'], ENT_QUOTES, 'UTF-8')
                    : 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&background=0652d6&color=fff&size=150'; ?>
                <img src="<?= $profilePhoto; ?>" alt="Foto Profil" class="nav-profile-img" id="profile">
            </div>
        </div>
    </div>
    <div class="profile-menu" id="profile-menu">
        <ul class="profile-item">

            <?php if (isset($_SESSION['username'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Penjual'): ?>
                    <li>
                        <a href="<?= BASE_URL; ?>/dashboard">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL; ?>/profile/<?= $_SESSION['username']; ?>">Profile</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?= BASE_URL; ?>/profile/<?= $_SESSION['username']; ?>">Profile</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL; ?>/profile/<?= $_SESSION['username']; ?>/history">History</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= BASE_URL; ?>/logout" class="logout">Log out</a>
                </li>

            <?php else: ?>
                <a href="<?= BASE_URL; ?>/login" class="login">Log In</a>
            <?php endif; ?>
        </ul>
    </div>
</nav>