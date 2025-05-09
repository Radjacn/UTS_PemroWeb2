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

// Ambil data mahasiswa
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $db->prepare("SELECT m.*, j.nama_jurusan FROM mahasiswa m JOIN jurusan j ON m.jurusan_id = j.id WHERE m.nama_mahasiswa LIKE :search OR m.email LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $db->query("SELECT m.*, j.nama_jurusan FROM mahasiswa m JOIN jurusan j ON m.jurusan_id = j.id");
}
$mahasiswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mahasiswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Include Sidebar and Navbar -->
        <?php include '../navbar_sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Kelola Mahasiswa</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="tambah_mahasiswa.php" class="btn btn-success">Tambah Mahasiswa</a>
                    <form method="GET" class="d-flex" style="max-width: 300px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/email..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Email</th>
                            <th>Jurusan</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <tr>
                                <td><?= $mahasiswa['id'] ?></td>
                                <td><?= $mahasiswa['NIM'] ?></td>
                                <td><?= $mahasiswa['nama_mahasiswa'] ?></td>
                                <td><?= $mahasiswa['email'] ?></td>
                                <td><?= $mahasiswa['nama_jurusan'] ?></td>
                                <td><?= $mahasiswa['password'] ?></td>
                                <td>
                                    <a href="edit_mahasiswa.php?id=<?= $mahasiswa['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="hapus_mahasiswa.php?id=<?= $mahasiswa['id'] ?>" class="btn btn-danger">Hapus</a>
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