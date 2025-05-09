<?php
// public/mahasiswa/edit_krs.php

session_start();
if (!isset($_SESSION['mahasiswa'])) {
    header('Location: index.php');
    exit;
}

require_once '../../vendor/autoload.php';

use App\Database\Database;
use App\Services\KrsService;

$db = (new Database())->connect();
$idMahasiswa = $_SESSION['mahasiswa']['id'];

$krsService = new KrsService($db);
$maxSKS = $krsService->getMaxSKS();
$errors = [];

// Simpan perubahan KRS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $krsService->simpanKRS($idMahasiswa, $_POST['mata_kuliah'] ?? []);
        header("Location: krs.php");
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

$mkList = $krsService->getMataKuliahList();
$krsSekarang = $krsService->getKrsMahasiswa($idMahasiswa);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const sksMap = {};
            const totalSKSDisplay = document.getElementById('totalSKS');
            const maxSKS = <?= $maxSKS ?>;

            <?php foreach ($mkList as $rows) : foreach ($rows as $row) : ?>
                    sksMap["<?= $row['mk_id'] ?>"] = <?= $row['sks'] ?>;
            <?php endforeach;
            endforeach; ?>

            function updateTotalSKS() {
                let total = 0;
                checkboxes.forEach(cb => {
                    const mkId = cb.name.match(/\d+/)[0];
                    if (cb.checked) {
                        total += sksMap[mkId];
                    }
                });

                totalSKSDisplay.textContent = total;

                checkboxes.forEach(cb => {
                    const mkId = cb.name.match(/\d+/)[0];
                    if (!cb.checked && total + sksMap[mkId] > maxSKS) {
                        cb.disabled = true;
                    } else {
                        cb.disabled = false;
                    }
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotalSKS));
            updateTotalSKS();
        });
    </script>
</head>

<body>
    <div class="container mt-4">
        <h2>Edit KRS Anda</h2>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) : ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <p>Total SKS yang dipilih: <strong><span id="totalSKS">0</span></strong> / <?= $maxSKS ?></p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mkList as $rows) : foreach ($rows as $row) : ?>
                            <tr>
                                <td><input type="checkbox" name="mata_kuliah[<?= $row['mk_id'] ?>]" value="<?= $row['dosen_id'] ?>" <?= in_array($row['mk_id'], $krsSekarang) ? 'checked' : '' ?>></td>
                                <td><?= $row['kode_mk'] ?></td>
                                <td><?= $row['nama_mk'] ?></td>
                                <td><?= $row['sks'] ?></td>
                                <td><?= $row['nama_dosen'] ?></td>
                            </tr>
                    <?php endforeach;
                    endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Simpan KRS</button>
        </form>
    </div>
</body>

</html>