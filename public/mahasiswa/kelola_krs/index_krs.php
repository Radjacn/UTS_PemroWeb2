<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

// Ambil NIM dari session login
$nim = $_SESSION['nim'] ?? null;

if (!$nim) {
    die("Akses tidak sah.");
}


// Ambil data mahasiswa berdasarkan NIM yang login
$stmt = $db->prepare("SELECT * FROM mahasiswa WHERE NIM = :nim");
$stmt->execute(['nim' => $nim]);
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mahasiswa) {
    die("Data mahasiswa tidak ditemukan.");
}

// Proses jika tombol cari ditekan
// Ambil data kelas
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE NIM LIKE :search OR nama_mahasiswa LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $db->query("SELECT * FROM mahasiswa");
}
$kelasList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>KRS Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once '../navbar_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Kartu Rencana Studi (KRS) - <?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="tambah_krs.php" class="btn btn-success">Tambah KRS</a>
                    <form method="GET" class="d-flex" style="max-width: 300px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/email..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Jurusan</th>
                            <th>Kelas</th>
                            <th>Tahun Akademik</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->prepare("
                            SELECT mk.nama_mk, d.nama_dosen, j.nama_jurusan, k.nama_kelas, krs.tahun_akademik, krs.semester
                            FROM krs 
                            JOIN mata_kuliah mk ON krs.mata_kuliah_id = mk.id
                            JOIN dosen d ON krs.dosen_id = d.id
                            JOIN jurusan j ON krs.jurusan_id = j.id
                            JOIN kelas k ON krs.kelas_id = k.id
                            WHERE krs.mahasiswa_id = :mahasiswa_id
                        ");
                        $stmt->execute(['mahasiswa_id' => $mahasiswa['id']]);
                        $krsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($krsList) :
                            foreach ($krsList as $row) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                                    <td><?= htmlspecialchars($row['tahun_akademik']) ?></td>
                                    <td><?= htmlspecialchars($row['semester']) ?></td>
                                </tr>
                            <?php endforeach;
                        else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data KRS.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>