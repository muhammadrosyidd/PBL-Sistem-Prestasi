<?php 
$use_driver = 'sqlsrv'; // mysql atau sqlsrv 
$host = "DAYDREAMER"; //'localhost'; 
$username = ''; //'sa'; 
$password = ''; 
$database = 'PRESTASI'; 
$db; 

if ($use_driver == 'mysql') { 
    try { 
        $db = new mysqli('localhost', $username, $password, $database); 
        
        if ($db->connect_error) { 
            die('Connection DB failed: ' . $db->connect_error); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
} else if ($use_driver == 'sqlsrv') { 
    $credential = [ 
        'Database' => $database, 
        'UID' => $username, 
        'PWD' => $password 
    ]; 
    
    try { 
        $db = sqlsrv_connect($host, $credential); 
        
        if (!$db) { 
            $msg = sqlsrv_errors(); 
            die($msg[0]['message']); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
}

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_lomba = isset($_POST['jenis_lomba']) ? $_POST['jenis_lomba'] : null;
    $tingkat_lomba_id = isset($_POST['tingkat_lomba_id']) ? $_POST['tingkat_lomba_id'] : null;
    $tanggal_pelaksanaan = isset($_POST['tanggal_pelaksanaan']) ? $_POST['tanggal_pelaksanaan'] : null;
    $link_pendaftaran = isset($_POST['link_pendaftaran']) ? $_POST['link_pendaftaran'] : null;
    $penyelenggara = isset($_POST['penyelenggara']) ? $_POST['penyelenggara'] : null;

    // Proses upload file
    if (isset($_FILES["gambar_poster"]) && $_FILES["gambar_poster"]["error"] == UPLOAD_ERR_OK) {
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
                echo "File ". htmlspecialchars(basename($_FILES["gambar_poster"]["name"])). " telah diupload.";

                // Simpan informasi ke database
                $sql = "INSERT INTO infolomba (gambar_poster, jenis_lomba, tingkat_lomba_id, tanggal_pelaksanaan, link_pendaftaran, penyelenggara)
                        VALUES (?, ?, ?, ?, ?, ?)";

                $params = [$targetFile, $jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara];

                $stmt = sqlsrv_query($db, $sql, $params);

                if ($stmt) {
                    header("Location: informasiLomba.php");
                    exit; // Ensure no further code is executed after redirect
                } else {
                    $msg = sqlsrv_errors();
                    echo "Error: " . $msg[0]['message'];
                }
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }
    } else {
        echo "File gambar tidak diupload atau terjadi kesalahan.";
    }
}

// Menutup koneksi
if ($use_driver == 'mysql') {
    $db->close();
} else if ($use_driver == 'sqlsrv') {
    sqlsrv_close($db);
}
?>