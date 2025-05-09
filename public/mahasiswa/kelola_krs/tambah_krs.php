<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../login.php");
    exit;
}
require_once '../../../config/Database.php';
$db = $conn;

// Ambil data dari tabel lain
$matkulList = $db->query("SELECT * FROM mata_kuliah")->fetchAll(PDO::FETCH_ASSOC);
$dosenList = $db->query("SELECT * FROM dosen")->fetchAll(PDO::FETCH_ASSOC);
$jurusanList = $db->query("SELECT * FROM jurusan")->fetchAll(PDO::FETCH_ASSOC);
$kelasList = $db->query("SELECT * FROM kelas")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar dan Sidebar -->
        <?php include_once '../navbar_sidebar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content pt-4">
                <div class="container">
                    <h2>Tambah KRS</h2>
                    <form action="simpan_krs.php" method="POST">
                        <div class="mb-3">
                            <label for="mata_kuliah" class="form-label">Pilih Mata Kuliah</label>
                            <select class="form-control" name="mata_kuliah_id" required>
                                <option value="">Pilih Mata Kuliah</option>
                                <?php foreach ($matkulList as $matkul) : ?>
                                    <option value="<?= $matkul['id'] ?>"><?= $matkul['nama_mk'] ?></option>
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
                        <div class="mb-3">
                            <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" placeholder="Contoh: 2024/2025" required>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-control" name="semester" required>
                                <option value="">Pilih Semester</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan KRS</button>
                    </form>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>