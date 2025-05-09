<?php
// config/Database.php

$host = 'localhost'; // Ganti dengan host database Anda
$db_name = 'kelolamhs_radjangili'; // Ganti dengan nama database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda

try {
    // Membuat koneksi PDO
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    // Mengatur error mode menjadi Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menangkap error koneksi
    echo "Connection error: " . $e->getMessage();
}
