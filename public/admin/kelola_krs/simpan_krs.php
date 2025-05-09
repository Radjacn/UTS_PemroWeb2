<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';

$db = $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'] ?? null;
    $mata_kuliah_id = $_POST['mata_kuliah_id'] ?? null;
    $dosen_id = $_POST['dosen_id'] ?? null;
    $jurusan_id = $_POST['jurusan_id'] ?? null;
    $kelas_id = $_POST['kelas_id'] ?? null;

    if ($mahasiswa_id && $mata_kuliah_id && $dosen_id && $jurusan_id && $kelas_id) {
        try {
            $stmt = $db->prepare("INSERT INTO krs (mahasiswa_id, mata_kuliah_id, dosen_id, jurusan_id, kelas_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$mahasiswa_id, $mata_kuliah_id, $dosen_id, $jurusan_id, $kelas_id]);

            header("Location: index_krs.php?success=1");
            exit;
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Gagal menyimpan data: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Semua field harus diisi!</div>";
    }
} else {
    header("Location: index_krs.php");
    exit;
}
