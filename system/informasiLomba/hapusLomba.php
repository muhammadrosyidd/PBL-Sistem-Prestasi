<?php
$use_driver = 'sqlsrv'; // Pilihan driver ('mysql' untuk MySQL, 'sqlsrv' untuk SQL Server)
$host = "localhost"; // Host database
$username = ''; // Username database
$password = ''; // Password database
$database = 'PRESTASI'; // Nama database

$db = null; // Inisialisasi variabel koneksi

// Membuka koneksi ke SQL Server
if ($use_driver == 'sqlsrv') {
    try {
        // Koneksi ke SQL Server
        $connectionInfo = array( "Database"=>$database, "UID"=>$username, "PWD"=>$password);
        $db = sqlsrv_connect( $host, $connectionInfo );

        if( !$db ) {
            die('Connection to database failed: ' . print_r(sqlsrv_errors(), true));
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link_pendaftaran = isset($_POST['link_pendaftaran']) ? $_POST['link_pendaftaran'] : '';

    if (!empty($link_pendaftaran)) {
        // Menggunakan prepared statement untuk keamanan
        $querySelect = "SELECT gambar_poster FROM infolomba WHERE link_pendaftaran = ?";
        $stmtSelect = sqlsrv_prepare($db, $querySelect, array(&$link_pendaftaran));

        if ($stmtSelect) {
            $resultSelect = sqlsrv_execute($stmtSelect);
            if ($resultSelect) {
                $row = sqlsrv_fetch_array($stmtSelect, SQLSRV_FETCH_ASSOC);

                $posterFile = $row['gambar_poster'] ?? null;

                // Hapus file gambar jika ada
                if (!empty($posterFile)) {
                    $filePath = 'Poster Lomba/' . $posterFile; // Sesuaikan dengan path folder Anda
                    if (file_exists($filePath)) {
                        unlink($filePath); // Menghapus file
                    }
                }

                sqlsrv_free_stmt($stmtSelect);
            } else {
                die("Error executing SELECT query: " . print_r(sqlsrv_errors(), true));
            }
        } else {
            die("Error preparing SELECT query: " . print_r(sqlsrv_errors(), true));
        }

        // SQL untuk menghapus data
        $queryDelete = "DELETE FROM infolomba WHERE link_pendaftaran = ?";
        $stmtDelete = sqlsrv_prepare($db, $queryDelete, array(&$link_pendaftaran));

        if ($stmtDelete) {
            $resultDelete = sqlsrv_execute($stmtDelete);
            if ($resultDelete) {
                // Redirect setelah berhasil menghapus
                header("Location: dataInformasiLomba.php");
                exit();
            } else {
                echo "Error: Data not found or could not be deleted.";
            }

            sqlsrv_free_stmt($stmtDelete);
        } else {
            die("Error preparing DELETE query: " . print_r(sqlsrv_errors(), true));
        }
    } else {
        echo "Lomba tidak ditemukan.";
    }
}

// Menutup koneksi
if ($db !== null) {
    sqlsrv_close($db);
}
?>
