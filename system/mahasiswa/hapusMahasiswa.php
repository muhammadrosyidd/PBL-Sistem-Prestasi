<?php 
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';

    if (!empty($nim)) {
        // Membuat koneksi
        $conn = $db->getConnection();

        // Memeriksa apakah NIM digunakan di tabel prestasi
        $queryPrestasi = "SELECT COUNT(*) AS count FROM prestasi WHERE nim = ?";
        $stmtPrestasi = sqlsrv_query($conn, $queryPrestasi, array($nim));
        $resultPrestasi = sqlsrv_fetch_array($stmtPrestasi);

        if ($resultPrestasi['count'] > 0) {
            // Jika NIM masih digunakan di tabel prestasi, tampilkan alert dan hentikan proses penghapusan
            echo "<script>
                    alert('Data gagal dihapus karena masih terpakai di tabel prestasi.');
                    window.location.href = 'dataMahasiswa.php';
                  </script>";
            exit();
        }

        // Mulai transaksi untuk menjaga konsistensi
        sqlsrv_begin_transaction($conn);

        try {
            // Menghapus data dari tabel mahasiswa berdasarkan nim
            $queryMahasiswa = "DELETE FROM mahasiswa WHERE nim = ?";
            $stmtMahasiswa = sqlsrv_query($conn, $queryMahasiswa, array($nim));

            if ($stmtMahasiswa === false) {
                throw new Exception("Error menghapus data di tabel mahasiswa.");
            }

            // Menghapus data dari tabel user berdasarkan nim dan role = 3
            $queryUser = "DELETE FROM [user] WHERE username = ? AND role = 3";
            $stmtUser = sqlsrv_query($conn, $queryUser, array($nim));

            if ($stmtUser === false) {
                throw new Exception("Error menghapus data di tabel user dengan role = 3.");
            }

            // Commit transaksi jika berhasil
            sqlsrv_commit($conn);

            // Redirect jika berhasil
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = 'dataMahasiswa.php';
                  </script>";
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            sqlsrv_rollback($conn);

            // Menampilkan error
            echo "<script>
                    alert('Error: " . $e->getMessage() . "');
                    window.location.href = 'dataMahasiswa.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('NIM tidak ditemukan.');
                window.location.href = 'dataMahasiswa.php';
              </script>";
    }
}
?>
