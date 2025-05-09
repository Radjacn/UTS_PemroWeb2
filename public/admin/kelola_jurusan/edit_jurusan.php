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
    header("Location: index_jurusan.php");
    exit;
}

// Ambil data jurusan
$stmt = $db->prepare("SELECT * FROM jurusan WHERE id = ?");
$stmt->execute([$id]);
$jurusan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$jurusan) {
    header("Location: index_jurusan.php");
    exit;
}


// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_jurusan = $_POST['nama_jurusan'];

    $updateStmt = $db->prepare("UPDATE jurusan SET  nama_jurusan = ? WHERE id = ?");
    $updateStmt->execute([$nama_jurusan, $id]);

    header("Location: index_jurusan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../navbar_sidebar.php'; ?>
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Edit Jurusan</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nama_jurusan" class="form-label">Jurusan</label>
                        <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" value="<?= $jurusan['nama_jurusan'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index_jurusan.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>