<?php
require_once 'config/Database.php';
$db = $conn;

if (!isset($_GET['id'])) {
    echo "ID Mahasiswa tidak tersedia.";
    exit;
}

$id = $_GET['id'];

// Ambil data mahasiswa
$stmt = $db->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mahasiswa) {
    echo "Mahasiswa tidak ditemukan.";
    exit;
}

// Ambil data KRS mahasiswa
$query = "
    SELECT 
        mk.nama_mk AS nama_mk, 
        mk.sks, 
        d.nama_dosen AS nama_dosen,
        j.id AS jurusan_id,
        k.nama_kelas AS nama_kelas
    FROM krs
    JOIN mata_kuliah mk ON krs.mata_kuliah_id = mk.id
    JOIN dosen d ON krs.dosen_id = d.id
    JOIN jurusan j ON krs.jurusan_id = j.id
    JOIN kelas k ON krs.kelas_id = k.id
    WHERE krs.mahasiswa_id = ?
";

$stmt = $db->prepare($query);
$stmt->execute([$id]);
$krsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total SKS
$totalSKS = array_sum(array_column($krsList, 'sks'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lihat KRS Mahasiswa</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="mb-4">Data KRS Mahasiswa</h2>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Nama:</strong> <?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></p>
                <p><strong>NIM:</strong> <?= htmlspecialchars($mahasiswa['NIM']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($mahasiswa['email']) ?></p>
            </div>
        </div>

        <h4>Daftar Mata Kuliah yang Diambil</h4>
        <?php if (count($krsList) > 0) : ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Mata Kuliah</th>
                        <th>Nama Kelas</th>
                        <th>SKS</th>
                        <th>Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($krsList as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['sks']) ?></td>
                            <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-secondary fw-bold">
                        <td class="text-center" colspan="2">Total SKS</td>
                        <td colspan="1"><?= $totalSKS ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-danger">Mahasiswa belum mengambil mata kuliah.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>