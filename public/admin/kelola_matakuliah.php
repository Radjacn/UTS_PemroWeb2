<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Hubungkan ke database
require_once '../../config/Database.php';

// Gunakan variabel $conn yang sudah didefinisikan dalam Database.php
$db = $conn;

// Ambil data mata kuliah
$stmt = $db->query("SELECT * FROM mata_kuliah");
$mataKuliahList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Kuliah</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Sidebar & Navbar -->
        <?php include 'navbar_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Kelola Mata Kuliah</h2>
                <a href="tambah_mata_kuliah.php" class="btn btn-success mb-3">Tambah Mata Kuliah</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Kode</th>
                            <th>SKS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mataKuliahList as $mataKuliah) : ?>
                            <tr>
                                <td><?= $mataKuliah['id'] ?></td>
                                <td><?= $mataKuliah['nama_mk'] ?></td>
                                <td><?= $mataKuliah['kode_mk'] ?></td>
                                <td><?= $mataKuliah['sks'] ?></td>
                                <td>
                                    <a href="edit_mata_kuliah.php?id=<?= $mataKuliah['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus_mata_kuliah.php?id=<?= $mataKuliah['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AdminLTE & Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>