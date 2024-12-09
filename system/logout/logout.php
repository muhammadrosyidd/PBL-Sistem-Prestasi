<?php
// Include file koneksi
require_once __DIR__ . '/../../config/Connection.php'; // Pastikan path ini benar

session_start(); // Mulai sesi

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    // Buat koneksi database
    $db = new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI");
    $conn = $db->connect();

    if (!$conn) {
        die("Connection failed: " . print_r(sqlsrv_errors(), true));
    }

    // Query untuk menghapus token dari database
    $sql = "UPDATE Info_Akun SET token = NULL WHERE token = ?";
    $params = array($token);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Hapus cookie token
    setcookie('token', '', time() - 3600, "/");
}

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan ke halaman login
header('Location: Sign-in/Pages-Sign-in/Sign-in.php');
exit;
?>