<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

require_once '../../../config/Database.php';
$db = $conn;

$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nama_mahasiswa LIKE :search OR NIM LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $db->query("SELECT * FROM mahasiswa");
}
$mahasiswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once '../navbar_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="container mt-5">
                <h2>Data Mahasiswa - KRS</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" class="d-flex" style="max-width: 300px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/NIM..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </form>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <tr>
                                <td><?= $mahasiswa['id'] ?></td>
                                <td><?= $mahasiswa['NIM'] ?></td>
                                <td><?= $mahasiswa['nama_mahasiswa'] ?></td>
                                <td><?= $mahasiswa['email'] ?></td>
                                <td>
                                    <a href="atur_krs.php?mahasiswa_id=<?= $mahasiswa['id'] ?>" class="btn btn-info">Atur KRS</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>