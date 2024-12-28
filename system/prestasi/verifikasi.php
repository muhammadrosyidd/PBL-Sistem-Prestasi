<?php
require_once __DIR__ . '/../../config/Connection.php';

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Buat objek koneksi dan ambil koneksi
$db = new Connection("localhost", "", "", "PRESTASI"); // Sesuaikan dengan pengaturan Anda
$conn = $db->connect(); // Mendapatkan koneksi langsung dari objek Connection

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
    $stmt = sqlsrv_prepare($conn, $sql, $params); // Gunakan $conn yang diambil dari $db

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
