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
    header("Location: index_matkul.php");
    exit;
}

// Ambil data mata_kuliah
$stmt = $db->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->execute([$id]);
$mata_kuliah = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$mata_kuliah) {
    header("Location: index_matkul.php");
    exit;
}

// Ambil data dosen
$dosenStmt = $db->query("SELECT id, nama_dosen FROM dosen");
$dosenList = $dosenStmt->fetchAll(PDO::FETCH_ASSOC);

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_mk = $_POST['kode_mk'];
    $nama = $_POST['nama_mk'];
    $sks = $_POST['sks'];
    $dosen_id = $_POST['dosen_id'];

    $updateStmt = $db->prepare("UPDATE mata_kuliah SET kode_mk = ?, nama_mk = ?, sks = ?, dosen_id = ? WHERE id = ?");
    $updateStmt->execute([$kode_mk, $nama, $sks, $dosen_id, $id]);

    header("Location: index_matkul.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once '../navbar_sidebar.php'; ?>
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Edit Mata Kuliah</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" value="<?= $mata_kuliah['kode_mk'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" value="<?= $mata_kuliah['nama_mk'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="sks" class="form-label">SKS</label>
                        <input type="sks" class="form-control" id="sks" name="sks" value="<?= $mata_kuliah['sks'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_id" class="form-label">Dosen Pengampu</label>
                        <select class="form-control" id="dosen_id" name="dosen_id" required>
                            <?php foreach ($dosenList as $dosen) : ?>
                                <option value="<?= $dosen['id'] ?>" <?= $dosen['id'] == $mata_kuliah['dosen_id'] ? 'selected' : '' ?>>
                                    <?= $dosen['nama_dosen'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index_matkul.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>