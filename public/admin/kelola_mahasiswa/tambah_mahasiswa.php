<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

// Ambil data jurusan
$jurusanStmt = $db->query("SELECT id, nama_jurusan FROM jurusan");
$jurusanList = $jurusanStmt->fetchAll(PDO::FETCH_ASSOC);

// Proses tambah
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NIM = $_POST['NIM'];
    $nama = $_POST['nama_mahasiswa'];
    $email = $_POST['email'];
    $jurusan_id = $_POST['jurusan_id'];
    $password = $_POST['password'];

    $insertStmt = $db->prepare("INSERT INTO mahasiswa (NIM, nama_mahasiswa, email, jurusan_id, password) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->execute([$NIM, $nama, $email, $jurusan_id, $password]);

    header("Location: index_mahasiswa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../navbar_sidebar.php'; ?>
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Tambah Mahasiswa</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="NIM" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="NIM" name="NIM" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Mahasiswa</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan_id" class="form-label">Jurusan</label>
                        <select class="form-control" id="jurusan_id" name="jurusan_id" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusanList as $jurusan) : ?>
                                <option value="<?= $jurusan['id'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Mahasiswa</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Tambah</button>
                    <a href="index_mahasiswa.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>