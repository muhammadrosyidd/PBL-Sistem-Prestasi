<?php
$use_driver = 'mysql'; // Pilihan driver ('mysql' untuk MySQL)
$host = "localhost"; // Host database
$username = 'root'; // Username database
$password = ''; // Password database
$database = 'prestasi'; // Nama database

$db = null; // Inisialisasi variabel koneksi

// Membuka koneksi ke MySQL
if ($use_driver == 'mysql') {
    try {
        $db = new mysqli($host, $username, $password, $database);
        
        if ($db->connect_error) {
            die('Connection to database failed: ' . $db->connect_error);
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
        $stmtSelect = $db->prepare($querySelect);
        
        if ($stmtSelect) {
            $stmtSelect->bind_param("s", $link_pendaftaran);
            $stmtSelect->execute();
            $resultSelect = $stmtSelect->get_result();
            $row = $resultSelect->fetch_assoc();

            $posterFile = $row['gambar_poster'] ?? null;

            // Hapus file gambar jika ada
            if (!empty($posterFile)) {
                $filePath = 'Poster Lomba/' . $posterFile; // Sesuaikan dengan path folder Anda
                if (file_exists($filePath)) {
                    unlink($filePath); // Menghapus file
                }
            }

            $stmtSelect->close();
        } else {
            die("Error preparing SELECT query: " . $db->error);
        }

        // SQL untuk menghapus data
        $queryDelete = "DELETE FROM infolomba WHERE link_pendaftaran = ?";
        $stmtDelete = $db->prepare($queryDelete);

        if ($stmtDelete) {
            $stmtDelete->bind_param("s", $link_pendaftaran);
            $stmtDelete->execute();

            if ($stmtDelete->affected_rows > 0) {
                header("Location: informasiLomba.php");
                exit();
            } else {
                echo "Error: Data not found or could not be deleted.";
            }

            $stmtDelete->close();
        } else {
            die("Error preparing DELETE query: " . $db->error);
        }
    } else {
        echo "Lomba tidak ditemukan.";
    }
}

// Menutup koneksi
if ($db !== null) {
    $db->close();
}
?>
