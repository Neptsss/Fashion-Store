<div class="page-content">
    <div class="page-header" style="margin-bottom: 30px;">
        <h2>Dashboard</h2>
        <p>Ringkasan performa dan statistik toko Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp <?= number_format($data['stats']['total_pendapatan'], 0, ',', '.') ?></div>
        </div>
        <div class="stat-card">
            <h3>Produk Terjual</h3>
            <div class="value blue"><?= $data['stats']['total_terjual'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Produk</h3>
            <div class="value"><?= $data['stats']['total_produk'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Total Kategori</h3>
            <div class="value"><?= $data['stats']['total_kategori'] ?></div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="table-container" style="padding: 24px;">
        <h3 style="margin-bottom: 20px; font-size: 16px; color: var(--text-main);">Grafik Penjualan Bulanan (Tahun <?= date('Y') ?>)</h3>
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chartData = <?= $data['chart_data'] ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>