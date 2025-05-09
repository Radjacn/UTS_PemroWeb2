<?php
session_start();
require_once 'config/Database.php'; // pastikan koneksi database tersedia
$db = $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Admin manual (jika masih ingin)
    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $username;
        header('Location: public/admin/dashboard.php');
        exit;
    }

    // Cek di tabel mahasiswa berdasarkan NIM
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE NIM = :nim");
    $stmt->execute(['nim' => $username]);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($mahasiswa && $password === $mahasiswa['password']) { // sesuaikan jika menggunakan password_hash()
        $_SESSION['role'] = 'mahasiswa';
        $_SESSION['username'] = $mahasiswa['nama_mahasiswa'];
        $_SESSION['nim'] = $mahasiswa['NIM'];
        $_SESSION['mahasiswa_id'] = $mahasiswa['id'];
        header('Location: public/mahasiswa/dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistem KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-box h3 {
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-login {
            border-radius: 10px;
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
        }

        .btn-login:hover {
            background-color: #084cdf;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h3>Login Sistem KRS</h3>
        <?php if (isset($error)) : ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-login">Login</button>
            </div>
        </form>
    </div>
</body>

</html>