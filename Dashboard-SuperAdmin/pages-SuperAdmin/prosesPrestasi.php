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
    // Data untuk tabel prestasi
    $nimMahasiswa = $_POST['nimMahasiswa'];
    $peran = $_POST['peran'];
    $judulKompetisi = $_POST['judulKompetisi'];
    $tempatKompetisi = $_POST['tempatKompetisi'];
    $tingkatKompetisi = $_POST['tingkatKompetisi'];
    $linkKompetisi = $_POST['linkKompetisi'];
    $jumlahPeserta = $_POST['jumlahPeserta'];
    $peringkatJuara = $_POST['peringkatJuara'];
    $tanggalMulai = $_POST['tanggalMulai'];
    $tanggalAkhir = $_POST['tanggalAkhir'];
    $noSuratTugas = $_POST['noSuratTugas'];
    $tanggalSuratTugas = $_POST['tanggalSuratTugas'];
    
    // Proses upload file
    $targetDir = "Prestasi/"; 
    $fileSuratTugas = $targetDir . uniqid() . '_' . basename($_FILES["fileSuratTugas"]["name"]); 
    $fileSertifikat = $targetDir . uniqid() . '_' . basename($_FILES["fileSertifikat"]["name"]); 
    $fotoKegiatan = $targetDir . uniqid() . '_' . basename($_FILES["fotoKegiatan"]["name"]); 
    $uploadOk = 1;

    // Cek ukuran file dan format untuk file surat tugas
    if ($_FILES["fileSuratTugas"]["size"] > 5000000 || $_FILES["fileSertifikat"]["size"] > 5000000 || $_FILES["fotoKegiatan"]["size"] > 5000000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Cek apakah file gambar adalah gambar sebenarnya untuk foto kegiatan
    $check = getimagesize($_FILES["fotoKegiatan"]["tmp_name"]);
    if ($check === false) {
        echo "File foto kegiatan bukan gambar.";
        $uploadOk = 0;
    }

    // Hanya izinkan format file tertentu untuk file surat tugas, sertifikat, dan foto kegiatan
    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileTypeSuratTugas = strtolower(pathinfo($fileSuratTugas, PATHINFO_EXTENSION));
    $fileTypeSertifikat = strtolower(pathinfo($fileSertifikat, PATHINFO_EXTENSION));
    $fileTypeFotoKegiatan = strtolower(pathinfo($fotoKegiatan, PATHINFO_EXTENSION));

    if (!in_array($fileTypeSuratTugas, $allowedFileTypes) || !in_array($fileTypeSertifikat, $allowedFileTypes) || !in_array($fileTypeFotoKegiatan, $allowedFileTypes)) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk di-set ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file tidak diupload.";
    } else {
        // Jika semua cek lulus, coba upload file
        if (move_uploaded_file($_FILES["fileSuratTugas"]["tmp_name"], $fileSuratTugas) &&
            move_uploaded_file($_FILES["fileSertifikat"]["tmp_name"], $fileSertifikat) &&
            move_uploaded_file($_FILES["fotoKegiatan"]["tmp_name"], $fotoKegiatan)) {
            
            echo "File telah diupload.";

            // Simpan informasi ke tabel prestasi
            $sqlPrestasi = "INSERT INTO prestasi (nim_mahasiswa, peran, judul_kompetisi, tempat_kompetisi, tingkat_kompetisi, link_kompetisi, jumlah_peserta, peringkat_juara, tanggal_mulai, tanggal_akhir, no_surat_tugas, tanggal_surat_tugas, file_surat_tugas, file_sertifikat, foto_kegiatan, status_prestasi)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Belum Terverifikasi')";
            $paramsPrestasi = [$nimMahasiswa, $peran, $judulKompetisi, $tempatKompetisi, $tingkatKompetisi, $linkKompetisi, $jumlahPeserta, $peringkatJuara, $tanggalMulai, $tanggalAkhir, $noSuratTugas, $tanggalSuratTugas, $fileSuratTugas, $fileSertifikat, $fotoKegiatan];
            $stmtPrestasi = sqlsrv_query($db, $sqlPrestasi, $paramsPrestasi);

            if ($stmtPrestasi) {
                header("Location: dataPrestasi.php");
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file.";
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