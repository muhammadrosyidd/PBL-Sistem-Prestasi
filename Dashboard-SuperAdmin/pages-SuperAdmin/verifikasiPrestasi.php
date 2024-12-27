<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan koneksi database
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // Nama server
$username = ''; // Username database
$password = ''; // Password database
$database = 'PRESTASI'; // Nama database
$db = null; // Variabel koneksi

if ($use_driver == 'sqlsrv') {
    // Konfigurasi koneksi untuk SQL Server
    $connectionOptions = [
        'Database' => $database,
        'UID' => $username,
        'PWD' => $password
    ];

    try {
        $db = sqlsrv_connect($host, $connectionOptions);
        if (!$db) {
            die("Connection failed: " . print_r(sqlsrv_errors(), true));
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Driver database tidak didukung.");
}

// Periksa apakah form telah disubmit
if (isset($_POST['verifikasi'])) {
    // Validasi input
    $prestasi_id = $_POST['prestasi_id'] ?? null;
    if (!$prestasi_id) {
        die("ID Prestasi tidak ditemukan.");
    }

    // Siapkan pernyataan SQL untuk memperbarui status verifikasi
    $sql = "UPDATE prestasi SET verifikasi_status = 'Sudah Terverifikasi' WHERE prestasi_id = ?";
    $params = [$prestasi_id]; // Parameter untuk query

    // Persiapkan pernyataan
    $stmt = sqlsrv_prepare($db, $sql, $params);

    if ($stmt) {
        // Eksekusi pernyataan
        if (sqlsrv_execute($stmt)) {
            // Redirect dengan pesan sukses
            header("Location: dataPrestasi.php?message=success");
        } else {
            // Pesan kesalahan
            die("Terjadi kesalahan saat memverifikasi prestasi: " . print_r(sqlsrv_errors(), true));
        }
    } else {
        die("Terjadi kesalahan dalam persiapan pernyataan: " . print_r(sqlsrv_errors(), true));
    }
} else {
    die("Aksi tidak valid.");
}
?>