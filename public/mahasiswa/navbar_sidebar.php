<?php
// Ambil nama file aktif (misalnya: index_dosen.php)
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="/kelola-mahasiswa-radja/public/admin/logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/kelola-mahasiswa-radja/public/admin/dashboard.php" class="brand-link">
        <span class="brand-text font-weight-light">Mahasiswa Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/kelola-mahasiswa-radja/public/mahasiswa/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kelola-mahasiswa-radja/public/mahasiswa/kelola_krs/index_krs.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Kelola KRS</p>
                    </a>
                </li>
                <!-- Tambahkan menu lainnya juga dengan prefix absolut yang sama -->
            </ul>
        </nav>
    </div>
</aside>