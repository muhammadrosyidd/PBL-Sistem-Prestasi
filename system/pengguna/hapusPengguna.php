<?php 
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    if (!empty($username)) {
        // Membuat koneksi
        $conn = $db->getConnection();

        // Mulai transaksi untuk menjaga konsistensi
        sqlsrv_begin_transaction($conn);

        try {
            // Menghapus data terkait di tabel admin terlebih dahulu
            $queryAdmin = "DELETE FROM admin WHERE username = ?";
            $stmtAdmin = sqlsrv_query($conn, $queryAdmin, array($username));

            if ($stmtAdmin === false) {
                throw new Exception("Error menghapus data di tabel admin.");
            }

            // Menghapus data terkait di tabel superadmin
            $querySuperadmin = "DELETE FROM superadmin WHERE username = ?";
            $stmtSuperadmin = sqlsrv_query($conn, $querySuperadmin, array($username));

            if ($stmtSuperadmin === false) {
                throw new Exception("Error menghapus data di tabel superadmin.");
            }

            // Menghapus data dari tabel user
            $queryUser = "DELETE FROM [user] WHERE username = ?";
            $stmtUser = sqlsrv_query($conn, $queryUser, array($username));

            if ($stmtUser === false) {
                throw new Exception("Error menghapus data di tabel user.");
            }

            // Commit transaksi jika berhasil
            sqlsrv_commit($conn);

            // Redirect jika berhasil
            header("Location: dataPengguna.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            sqlsrv_rollback($conn);

            // Menampilkan error
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Username tidak ditemukan.";
    }
}
?>
