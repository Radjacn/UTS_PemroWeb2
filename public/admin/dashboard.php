<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}


// Hubungkan ke database
require_once '../../config/Database.php';

// Gunakan variabel $conn yang sudah didefinisikan dalam Database.php
$db = $conn;

// Ambil total mahasiswa, jurusan, dosen, dan mata kuliah
$stmt = $db->query("SELECT COUNT(*) as total FROM mahasiswa");
$totalMahasiswa = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM jurusan");
$totalJurusan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM dosen");
$totalDosen = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM kelas");
$totalKelas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
// Ambil jumlah mahasiswa per jurusan
$stmt = $db->query("
    SELECT j.nama_jurusan, COUNT(m.id) as jumlah_mahasiswa
    FROM mahasiswa m
    JOIN jurusan j ON m.jurusan_id = j.id
    GROUP BY j.nama_jurusan
");
$mahasiswaPerJurusan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil jumlah mata kuliah yang diambil oleh mahasiswa
$stmt = $db->query("
    SELECT mk.nama_mk, COUNT(k.mata_kuliah_id) as jumlah_dikambil
    FROM krs k
    JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
    GROUP BY mk.nama_mk
");
$mataKuliahDikambil = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Include Sidebar and Navbar -->
        <?php include 'navbar_sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1>Welcome to Admin Dashboard</h1>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <!-- Data stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $totalMahasiswa ?></h3>
                                    <p>Total Mahasiswa</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $totalJurusan ?></h3>
                                    <p>Total Jurusan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-school"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $totalDosen ?></h3>
                                    <p>Total Dosen</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= $totalKelas ?></h3>
                                    <p>Total Kelas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik: Jumlah Mahasiswa per Jurusan -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Jumlah Mahasiswa per Jurusan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartMahasiswaJurusan"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik: Jumlah Mata Kuliah yang Diambil -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Jumlah Mata Kuliah yang Diambil</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartMataKuliahDikambil"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>&copy; Radja Kornelius Ngili || 2237400028</strong>
        </footer>
    </div>

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Chart.js -->
    <script>
        // Fungsi untuk menghasilkan warna acak
        function getRandomColor() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgba(${r}, ${g}, ${b}, 0.2)`; // Warna untuk background
        }

        // Fungsi untuk menghasilkan warna border acak
        function getRandomBorderColor() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgba(${r}, ${g}, ${b}, 1)`; // Warna untuk border
        }

        const mahasiswaJurusanData = {
            labels: <?= json_encode(array_column($mahasiswaPerJurusan, 'nama_jurusan')) ?>,
            datasets: [{
                label: 'Jumlah Mahasiswa per Jurusan',
                data: <?= json_encode(array_column($mahasiswaPerJurusan, 'jumlah_mahasiswa')) ?>,
                backgroundColor: [], // Array kosong untuk warna acak
                borderColor: [], // Array kosong untuk border warna acak
                borderWidth: 1
            }]
        };

        const mataKuliahData = {
            labels: <?= json_encode(array_column($mataKuliahDikambil, 'nama_mk')) ?>,
            datasets: [{
                label: 'Jumlah Mata Kuliah yang Diambil',
                data: <?= json_encode(array_column($mataKuliahDikambil, 'jumlah_dikambil')) ?>,
                backgroundColor: [], // Array kosong untuk warna acak
                borderColor: [], // Array kosong untuk border warna acak
                borderWidth: 1
            }]
        };

        // Mengisi warna acak ke dalam backgroundColor dan borderColor untuk mahasiswa per jurusan
        mahasiswaJurusanData.datasets[0].backgroundColor = mahasiswaJurusanData.labels.map(() => getRandomColor());
        mahasiswaJurusanData.datasets[0].borderColor = mahasiswaJurusanData.labels.map(() => getRandomBorderColor());

        // Mengisi warna acak ke dalam backgroundColor dan borderColor untuk mata kuliah yang diambil
        mataKuliahData.datasets[0].backgroundColor = mataKuliahData.labels.map(() => getRandomColor());
        mataKuliahData.datasets[0].borderColor = mataKuliahData.labels.map(() => getRandomBorderColor());

        const configMahasiswaJurusan = {
            type: 'bar',
            data: mahasiswaJurusanData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const configMataKuliahDikambil = {
            type: 'bar',
            data: mataKuliahData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        new Chart(document.getElementById('chartMahasiswaJurusan'), configMahasiswaJurusan);
        new Chart(document.getElementById('chartMataKuliahDikambil'), configMataKuliahDikambil);
    </script>

</body>

</html>