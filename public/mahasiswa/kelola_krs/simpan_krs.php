<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

// Validasi apakah semua field dikirim
if (
    empty($_POST['mata_kuliah_id']) ||
    empty($_POST['dosen_id']) ||
    empty($_POST['jurusan_id']) ||
    empty($_POST['kelas_id'])
) {
    die("Semua field harus diisi.");
}

$mata_kuliah_id = $_POST['mata_kuliah_id'];
$dosen_id = $_POST['dosen_id'];
$jurusan_id = $_POST['jurusan_id'];
$kelas_id = $_POST['kelas_id'];
$tahun_akademik = $_POST['tahun_akademik'] ?? date('Y') . '/' . (date('Y') + 1); // Default jika tidak dikirim
$semester = $_POST['semester'] ?? 'Ganjil';

// Ambil ID mahasiswa dari session NIM
$nim = $_SESSION['nim'];
$stmt = $db->prepare("SELECT id FROM mahasiswa WHERE NIM = :nim");
$stmt->execute(['nim' => $nim]);
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mahasiswa) {
    die("Mahasiswa tidak ditemukan.");
}

$mahasiswa_id = $mahasiswa['id'];

// Cek apakah sudah ada data KRS yang sama untuk mencegah duplikasi
$cek = $db->prepare("SELECT * FROM krs WHERE mahasiswa_id = :mahasiswa_id AND mata_kuliah_id = :mata_kuliah_id");
$cek->execute([
    'mahasiswa_id' => $mahasiswa_id,
    'mata_kuliah_id' => $mata_kuliah_id
]);

if ($cek->rowCount() > 0) {
    die("KRS untuk mata kuliah ini sudah ditambahkan.");
}

// Simpan ke database
$stmt = $db->prepare("INSERT INTO krs (mahasiswa_id, mata_kuliah_id, dosen_id, jurusan_id, kelas_id, tahun_akademik, semester)
    VALUES (:mahasiswa_id, :mata_kuliah_id, :dosen_id, :jurusan_id, :kelas_id, :tahun_akademik, :semester)");

$sukses = $stmt->execute([
    'mahasiswa_id' => $mahasiswa_id,
    'mata_kuliah_id' => $mata_kuliah_id,
    'dosen_id' => $dosen_id,
    'jurusan_id' => $jurusan_id,
    'kelas_id' => $kelas_id,
    'tahun_akademik' => $tahun_akademik,
    'semester' => $semester
]);

if ($sukses) {
    header("Location: index_krs.php?success=1");
    exit;
} else {
    die("Gagal menyimpan data KRS.");
}
