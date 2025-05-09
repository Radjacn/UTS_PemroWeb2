<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../login.php");
    exit;
}

$displayName = $_SESSION['nama_mahasiswa'] ?? $_SESSION['username'];
$mahasiswaId = $_SESSION['mahasiswa_id'];

require_once '../../config/Database.php';
$db = $conn;

// Total SKS yang diambil
$stmt = $db->prepare("
    SELECT SUM(mk.sks) as total_sks
    FROM krs k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE k.mahasiswa_id = ?
");
$stmt->execute([$mahasiswaId]);
$totalSKS = $stmt->fetch(PDO::FETCH_ASSOC)['total_sks'] ?? 0;

// Total Mata Kuliah yang diambil
$stmt = $db->prepare("
    SELECT COUNT(DISTINCT mata_kuliah_id) as total_mk
    FROM krs
    WHERE mahasiswa_id = ?
");
$stmt->execute([$mahasiswaId]);
$totalMK = $stmt->fetch(PDO::FETCH_ASSOC)['total_mk'] ?? 0;

// Informasi semester dan tahun ajaran
$stmt = $db->prepare("
    SELECT semester, tahun_ajaran
    FROM krs
    WHERE mahasiswa_id = ?
    ORDER BY tahun_ajaran DESC, semester DESC
    LIMIT 1
");


// Data SKS per mata kuliah
$stmt = $db->prepare("
    SELECT mk.nama_mk, mk.sks
    FROM krs k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    WHERE k.mahasiswa_id = ?
");
$stmt->execute([$mahasiswaId]);
$mataKuliahSKS = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'navbar_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1>Selamat datang, <?= htmlspecialchars($displayName) ?>!</h1>
                    <p>Anda sedang berada di Dashboard Mahasiswa.</p>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Total SKS -->
                        <div class="col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $totalSKS ?></h3>
                                    <p>Total SKS Diambil</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Mata Kuliah -->
                        <div class="col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $totalMK ?></h3>
                                    <p>Total Mata Kuliah Diambil</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Grafik SKS per Mata Kuliah -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">SKS per Mata Kuliah</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartSKS"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>&copy; 2025 Your Application</strong>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script>
        const mataKuliahLabels = <?= json_encode(array_column($mataKuliahSKS, 'nama_mk')) ?>;
        const mataKuliahSKS = <?= json_encode(array_column($mataKuliahSKS, 'sks')) ?>;

        function getRandomColor(opacity = 0.2) {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgba(${r}, ${g}, ${b}, ${opacity})`;
        }

        const backgroundColors = mataKuliahLabels.map(() => getRandomColor(0.2));
        const borderColors = mataKuliahLabels.map(() => getRandomColor(1));

        const ctx = document.getElementById('chartSKS').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mataKuliahLabels,
                datasets: [{
                    label: 'SKS',
                    data: mataKuliahSKS,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
</body>

</html>