<?php 
$use_driver = 'sqlsrv'; // mysql atau sqlsrv 
$host = "DAYDREAMER"; //'localhost'; 
$username = ''; //'sa'; 
$password = ''; 
$database = 'PencatatanPrestasi'; 
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
    $jenisLomba = $_POST['jenisLomba'];
    $tingkatLomba = $_POST['tingkatLomba'];
    $tanggalPelaksanaan = $_POST['tanggalPelaksanaan'];
    $linkPendaftaran = $_POST['linkPendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];

    // Proses upload file
    $targetDir = "Poster Lomba/"; 
    $targetFile = $targetDir . uniqid() . '_' . basename($_FILES["posterLomba"]["name"]); 
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["posterLomba"]["tmp_name"]);
    if ($check !== false) {
        echo "File adalah gambar - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["posterLomba"]["size"] > 500000) { // 500KB limit
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
        if (move_uploaded_file($_FILES["posterLomba"]["tmp_name"], $targetFile)) {
            echo "File ". htmlspecialchars(basename($_FILES["posterLomba"]["name"])). " telah diupload.";

        // Simpan informasi ke database
        $sql = "INSERT INTO informasiLomba (posterLomba, jenisLomba, tingkatLomba, tanggalPelaksanaan, linkPendaftaran, penyelenggara)
                VALUES (?, ?, ?, ?, ?, ?)";

        $params = [$targetFile, $jenisLomba, $tingkatLomba, $tanggalPelaksanaan, $linkPendaftaran, $penyelenggara];

        $stmt = sqlsrv_query($db, $sql, $params);

        if ($stmt) {
            header("Location: informasiLomba.php");
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        }
    }
}
    // Menutup koneksi
    if ($use_driver == 'mysql') {
        $db->close();
    } else if ($use_driver == 'sqlsrv') {
        sqlsrv_close($db);
    }
?>