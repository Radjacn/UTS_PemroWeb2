<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index_dosen.php");
    exit;
}

// Ambil data dosen
$stmt = $db->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$id]);
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$dosen) {
    header("Location: index_dosen.php");
    exit;
}


// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama_dosen'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    $updateStmt = $db->prepare("UPDATE dosen SET nidn = ?, nama_dosen = ?, nomor_telepon = ?, alamat = ? WHERE id = ?");
    $updateStmt->execute([$nidn, $nama, $nomor_telepon, $alamat, $id]);

    header("Location: index_dosen.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once '../navbar_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Edit Dosen</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control" id="nidn" name="nidn" value="<?= $dosen['nidn'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_dosen" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="<?= $dosen['nama_dosen'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                        <input type="nomor_telepon" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?= $dosen['nomor_telepon'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="alamat" class="form-control" id="alamat" name="alamat" value="<?= $dosen['alamat'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index_dosen.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>