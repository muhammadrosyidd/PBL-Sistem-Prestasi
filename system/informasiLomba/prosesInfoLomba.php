<?php
require_once __DIR__ . '/../../config/Connection.php';

// Inisialisasi koneksi ke database
  // Gantilah dengan password SQL Server Anda
$conn = $db->connect();

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_lomba = $_POST['jenis_lomba'];
    $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];

    // Proses upload file
    $targetDir = "Poster Lomba/";
    $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["gambar_poster"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["gambar_poster"]["tmp_name"]);
    if ($check !== false) {
        echo "File adalah gambar - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["gambar_poster"]["size"] > 500000) { // 500KB limit
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Hanya izinkan format file tertentu
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk di-set ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file tidak diupload.";
    } else {
        // Jika semua cek lulus, coba upload file
        if (move_uploaded_file($_FILES["gambar_poster"]["tmp_name"], $targetFile)) {
            echo "File " . htmlspecialchars(basename($_FILES["gambar_poster"]["name"])) . " telah diupload.";

            // Simpan informasi ke database
            $sql = "INSERT INTO infolomba (gambar_poster, jenis_lomba, tingkat_lomba_id, tanggal_pelaksanaan, link_pendaftaran, penyelenggara)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $params = array($targetFile, $jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara);

            // Eksekusi query dengan sqlsrv_query
            $stmt = sqlsrv_prepare($conn, $sql, $params);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_execute($stmt)) {
                echo "Data berhasil disimpan!";
                header("Location: dataInformasiLomba.php"); // Redirect ke halaman lain setelah sukses
            } else {
                echo "Error: " . print_r(sqlsrv_errors(), true);
            }

            sqlsrv_free_stmt($stmt);
        } else {
            echo "Maaf, terjadi kesalahan dalam proses upload file.";
        }
    }
}

// Menutup koneksi
$db->close();
?>
