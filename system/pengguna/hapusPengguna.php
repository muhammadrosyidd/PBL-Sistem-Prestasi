<?php 
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    if (!empty($username)) {
        // SQL untuk menghapus data
        $query = "DELETE FROM [user] WHERE username = ?"; // Menggunakan parameterized query untuk mencegah SQL injection

        // Mengambil koneksi dari object Connection
        $conn = $db->getConnection();

        // Eksekusi query menggunakan prepared statement untuk keamanan
        $stmt = sqlsrv_query($conn, $query, array($username));

        if ($stmt) {
            // Redirect jika berhasil
            header("Location: dataPengguna.php");
            exit();
        } else {
            // Menampilkan error jika gagal
            $msg = sqlsrv_errors();
            echo "Error: " . $msg[0]['message'];
        }
    } else {
        echo "Username tidak ditemukan.";
    }
}
?>

