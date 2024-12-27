<?php
ob_start();
session_start();
require_once __DIR__ . '/../../config/Connection.php';
require_once 'Login.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$connection = new Connection("DESKTOP-IVR2LTO", "", "", "PRESTASI");
if (!$connection->connect()) {
    die("Koneksi gagal: " . print_r(sqlsrv_errors(), true));
}

$login = new Login($connection);
$role = $login->authenticate($username, $password);

if ($role !== null) {
    $_SESSION['username'] = $username; // Simpan hanya username
    $_SESSION['role'] = $role; // Simpan role untuk kontrol akses

    switch ($role) {
        case 1: // Gunakan case 1, 2, 3, bukan string "1", "2", "3"
            header("Location: ../../system/pageSuperAdmin/dashboard.php");
            break;
        case 2:
            header("Location: ../../system/pageAdmin/dashboard.php");
            break;
        case 3:
            header("Location: ../../system/pageMahasiswa/dashboard.php");
            break;
        default:
            echo "Role tidak dikenali.";
    }
    exit(); // Pastikan exit() ada setelah header()
} else {
    echo "<p style='color: red;'>Username atau password salah.</p>";
}

ob_end_flush();
?>