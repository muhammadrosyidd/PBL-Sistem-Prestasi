<?php
ob_start(); 
session_start();
require_once __DIR__ . '/../../config/Connection.php'; // Pastikan path ini benar
require_once 'Login.php'; // Pastikan path ini benar

// Ambil data dari form
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Koneksi ke database
$connection = new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI");
if (!$connection->connect()) {
    die("Koneksi gagal: " . print_r(sqlsrv_errors(), true));
}

// Gunakan kelas Login untuk autentikasi
$login = new Login($connection);
$role = $login->authenticate($username, $password);

if ($role !== null) {
    // Simpan role ke session dan redirect berdasarkan role
    $_SESSION['role'] = $role;

    switch ($role) {
        case "1":
            header("Location: ../../system/pageSuperAdmin/dashboard.php");
            exit(); // Pastikan setelah header() tidak ada output
        case "2":
            header("Location: ../../system/pageAdmin/dashboard.php");
            exit();
        case "3":
            header("Location: ../../system/pageMahasiswa/dashboard.php");
            exit();
        default:
            echo "Role tidak dikenali.";
    }
} else {
    echo "<p style='color: red;'>Username atau password salah.</p>";
}
ob_end_flush();
?>
