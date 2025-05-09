<?php
// logout.php

// Mulai sesi
session_start();

// Hapus semua data sesi
session_unset();
session_destroy();

// Arahkan kembali ke halaman utama
header("Location: ../../index.php"); // Sesuaikan dengan lokasi logout.php berada
exit;
