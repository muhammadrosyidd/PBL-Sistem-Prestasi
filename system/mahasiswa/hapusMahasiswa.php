<?php 
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';

    if (!empty($nim)) {
        // Membuat koneksi
        $conn = $db->getConnection();

        try {
            // Menghapus data dari tabel user berdasarkan nim
            $queryUser = "DELETE FROM [mahasiswa] WHERE nim = ?";
            $stmtUser = sqlsrv_query($conn, $queryUser, array($nim));

            if ($stmtUser === false) {
                throw new Exception("Error menghapus data di tabel mahasiswa.");
            }

            // Jika berhasil, tampilkan alert dan redirect
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = 'dataMahasiswa.php';
                  </script>";
            exit();
        } catch (Exception $e) {
            // Menampilkan alert jika terjadi error
            $errorMessage = $e->getMessage();
            echo "<script>
                    alert('Error: $errorMessage');
                    window.location.href = 'dataMahasiswa.php';
                  </script>";
        }
    } else {
        // Alert jika NIM kosong
        echo "<script>
                alert('NIM tidak ditemukan.');
                window.location.href = 'dataMahasiswa.php';
              </script>";
    }
}
?>
