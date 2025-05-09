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
$mahasiswaQuery = $db->query("SELECT * FROM mahasiswa");
$mahasiswaList = $mahasiswaQuery->fetchAll(PDO::FETCH_ASSOC);

// Ambil data mata kuliah
$matkulQuery = $db->query("SELECT * FROM mata_kuliah");
$matkulList = $matkulQuery->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dosen
$dosenQuery = $db->query("SELECT * FROM dosen");
$dosenList = $dosenQuery->fetchAll(PDO::FETCH_ASSOC);

// Ambil data jurusan
$jurusanQuery = $db->query("SELECT * FROM jurusan");
$jurusanList = $jurusanQuery->fetchAll(PDO::FETCH_ASSOC);

// Ambil data kelas
$kelasQuery = $db->query("SELECT * FROM kelas");
$kelasList = $kelasQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Include Sidebar and Navbar -->
        <?php include_once '../navbar_sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Tambah KRS</h2>
                <form action="simpan_krs.php" method="POST">
                    <div class="mb-3">
                        <label for="mahasiswa" class="form-label">Pilih Mahasiswa</label>
                        <select class="form-control" name="mahasiswa_id" required>
                            <option value="">Pilih Mahasiswa</option>
                            <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                                <option value="<?= $mahasiswa['id'] ?>"><?= $mahasiswa['nama_mahasiswa'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mata_kuliah" class="form-label">Pilih Mata Kuliah</label>
                        <select class="form-control" name="mata_kuliah_id" required>
                            <option value="">Pilih Mata Kuliah</option>
                            <?php foreach ($matkulList as $matkul) : ?>
                                <option value="<?= $matkul['id'] ?>"><?= $matkul['nama_mata_kuliah'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dosen" class="form-label">Pilih Dosen Pengampu</label>
                        <select class="form-control" name="dosen_id" required>
                            <option value="">Pilih Dosen</option>
                            <?php foreach ($dosenList as $dosen) : ?>
                                <option value="<?= $dosen['id'] ?>"><?= $dosen['nama_dosen'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Pilih Jurusan</label>
                        <select class="form-control" name="jurusan_id" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($jurusanList as $jurusan) : ?>
                                <option value="<?= $jurusan['id'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Pilih Kelas</label>
                        <select class="form-control" name="kelas_id" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($kelasList as $kelas) : ?>
                                <option value="<?= $kelas['id'] ?>"><?= $kelas['nama_kelas'] ?></option>
                            <?php endforeach; ?>
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