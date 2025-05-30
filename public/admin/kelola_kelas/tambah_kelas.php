<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

// Proses tambah
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_kelas = $_POST['kode_kelas'];
    $nama = $_POST['nama_kelas'];

    $insertStmt = $db->prepare("INSERT INTO kelas (kode_kelas, nama_kelas) VALUES (?, ?)");
    $insertStmt->execute([$kode_kelas, $nama]);

    header("Location: index_kelas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../navbar_sidebar.php'; ?>
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Tambah Kelas</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="kode_kelas" class="form-label">Kode Kelas</label>
                        <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                    </div>
                    <button type="submit" class="btn btn-success">Tambah</button>
                    <a href="index_kelas.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>