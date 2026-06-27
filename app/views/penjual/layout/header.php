<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['judul']) ? $data['judul'] : 'Dashboard' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/dashboard.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 style="color: #2563eb; letter-spacing: 1px;">STELLAR & CO.</h1>
            <p>Penjual</p>
            <a href="<?= BASE_URL; ?>"
                class="btn-secondary"
                style="margin-top:15px;display:block;text-align:center;">
                <i class="bi bi-globe"></i>
                Lihat Website
            </a>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= BASE_URL; ?>/dashboard"
                    class="<?= $path == '/project_akhir/public/dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL; ?>/dashboard/products"
                    class="<?= strpos($path, '/dashboard/products') !== false ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL; ?>/dashboard/categories"
                    class="<?= strpos($path, '/dashboard/categories') !== false ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i> Kategori
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL; ?>/dashboard/orders"
                    class="<?= strpos($path, '/dashboard/orders') !== false ? 'active' : '' ?>">
                    <i class="bi bi-bag"></i> Pesanan
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL; ?>/logout" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <form>
                <div class="search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search..." name="search" value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
            </form>
        </header>
        <?php App\Core\Flasher::flash(); ?>