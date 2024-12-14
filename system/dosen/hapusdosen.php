<?php 
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dosen_id = isset($_POST['dosen_id']) ? $_POST['dosen_id'] : '';

    if (!empty($dosen_id)) {
        // Membuat koneksi
        $conn = $db->getConnection();

        // Mulai transaksi untuk menjaga konsistensi
        sqlsrv_begin_transaction($conn);

        try {
            // Mengecek apakah dosen_id masih terpakai dalam tabel prestasi
            $queryCheckPrestasi = "SELECT COUNT(*) FROM prestasi WHERE dosen_id = ?";
            $stmtCheckPrestasi = sqlsrv_query($conn, $queryCheckPrestasi, array($dosen_id));
            if ($stmtCheckPrestasi === false) {
                throw new Exception("Error saat memeriksa data di tabel prestasi.");
            }

            $row = sqlsrv_fetch_array($stmtCheckPrestasi, SQLSRV_FETCH_ASSOC);
            if ($row[''] > 0) {
                throw new Exception("Dosen ID ini masih digunakan dalam tabel prestasi, penghapusan tidak dapat dilakukan.");
            }

            // Menghapus data dosen dari tabel dosen jika tidak ada referensi di tabel prestasi
            $queryDosen = "DELETE FROM dosen WHERE dosen_id = ?";
            $stmtDosen = sqlsrv_query($conn, $queryDosen, array($dosen_id));

            if ($stmtDosen === false) {
                throw new Exception("Error menghapus data di tabel dosen.");
            }

            // Commit transaksi jika berhasil
            sqlsrv_commit($conn);

            // Redirect jika berhasil
            header("Location: dataDosen.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            sqlsrv_rollback($conn);

            // Menampilkan error
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Dosen ID tidak ditemukan.";
    }
}
?>
