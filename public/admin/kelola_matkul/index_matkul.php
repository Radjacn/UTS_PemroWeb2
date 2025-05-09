<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}
// Hubungkan ke database
require_once '../../../config/Database.php';

// Gunakan variabel $conn yang sudah didefinisikan dalam Database.php
$db = $conn;

$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $db->prepare("
        SELECT m.*, d.nama_dosen
        FROM mata_kuliah m
        JOIN dosen d ON m.dosen_id = d.id
        WHERE m.nama_mata_kuliah LIKE :search
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $db->query("
        SELECT m.*, d.nama_dosen
        FROM mata_kuliah m
        JOIN dosen d ON m.dosen_id = d.id
    ");
}
$mata_kuliahList = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        <!-- Include Sidebar and Navbar -->
        <?php include_once '../navbar_sidebar.php'; ?>


        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Kelola Mata Kuliah</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="tambah_matkul.php" class="btn btn-success">Tambah Mata Kuliah</a>
                    <form method="GET" class="d-flex" style="max-width: 300px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/email..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Dosen Pengampu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mata_kuliahList as $mata_kuliah) : ?>
                            <tr>
                                <td><?= $mata_kuliah['id'] ?></td>
                                <td><?= $mata_kuliah['kode_mk'] ?></td>
                                <td><?= $mata_kuliah['nama_mk'] ?></td>
                                <td><?= $mata_kuliah['sks'] ?></td>
                                <td><?= $mata_kuliah['nama_dosen'] ?></td>
                                <td>
                                    <a href="edit_matkul.php?id=<?= $mata_kuliah['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="hapus_matkul.php?id=<?= $mata_kuliah['id'] ?>" class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>