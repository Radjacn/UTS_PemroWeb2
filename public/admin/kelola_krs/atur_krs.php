<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

// Mendapatkan ID mahasiswa dari URL
$mahasiswa_id = $_GET['mahasiswa_id'] ?? null;
if (!$mahasiswa_id) {
    die("ID Mahasiswa tidak ditemukan.");
}

// Ambil data mahasiswa berdasarkan ID
$stmt = $db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
$stmt->execute(['id' => $mahasiswa_id]);
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$mahasiswa) {
    die("Mahasiswa tidak ditemukan.");
}

// Ambil data mata kuliah, dosen, jurusan, dan kelas
$mata_kuliah_stmt = $db->query("SELECT * FROM mata_kuliah");
$mata_kuliahList = $mata_kuliah_stmt->fetchAll(PDO::FETCH_ASSOC);

$dosen_stmt = $db->query("SELECT * FROM dosen");
$dosenList = $dosen_stmt->fetchAll(PDO::FETCH_ASSOC);

$jurusan_stmt = $db->query("SELECT * FROM jurusan");
$jurusanList = $jurusan_stmt->fetchAll(PDO::FETCH_ASSOC);

$kelas_stmt = $db->query("SELECT * FROM kelas");
$kelasList = $kelas_stmt->fetchAll(PDO::FETCH_ASSOC);

// Menangani pengiriman form KRS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = $_POST['mata_kuliah_id'];
    $dosen_id = $_POST['dosen_id'];
    $jurusan_id = $_POST['jurusan_id'];
    $kelas_id = $_POST['kelas_id'];
    $tahun_akademik = $_POST['tahun_akademik'];
    $semester = $_POST['semester'];

    // Menyimpan data KRS ke dalam database
    $stmt = $db->prepare("INSERT INTO krs (mahasiswa_id, mata_kuliah_id, dosen_id, jurusan_id, kelas_id, tahun_akademik, semester) 
                          VALUES (:mahasiswa_id, :mata_kuliah_id, :dosen_id, :jurusan_id, :kelas_id, :tahun_akademik, :semester)");
    $stmt->execute([
        'mahasiswa_id' => $mahasiswa_id,
        'mata_kuliah_id' => $mata_kuliah_id,
        'dosen_id' => $dosen_id,
        'jurusan_id' => $jurusan_id,
        'kelas_id' => $kelas_id,
        'tahun_akademik' => $tahun_akademik,
        'semester' => $semester
    ]);
    echo "<script>alert('KRS berhasil ditambahkan.'); window.location.href='index_krs.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur KRS - <?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once '../navbar_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Atur KRS untuk Mahasiswa: <?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></h2>

                <form method="POST">
                    <div class="mb-3">
                        <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
                        <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control" required>
                            <option value="">Pilih Mata Kuliah</option>
                            <?php foreach ($mata_kuliahList as $mata_kuliah) : ?>
                                <option value="<?= $mata_kuliah['id'] ?>"><?= $mata_kuliah['nama_mk'] ?> (<?= $mata_kuliah['kode_mk'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dosen_id" class="form-label">Dosen Pengampu</label>
                        <select name="dosen_id" id="dosen_id" class="form-control" required>
                            <option value="">Pilih Dosen</option>
                            <?php foreach ($dosenList as $dosen) : ?>
                                <option value="<?= $dosen['id'] ?>"><?= $dosen['nama_dosen'] ?> (<?= $dosen['nidn'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jurusan_id" class="form-label">Jurusan</label>
                        <select name="jurusan_id" id="jurusan_id" class="form-control" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($jurusanList as $jurusan) : ?>
                                <option value="<?= $jurusan['id'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($kelasList as $kelas) : ?>
                                <option value="<?= $kelas['id'] ?>"><?= $kelas['nama_kelas'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan KRS</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>