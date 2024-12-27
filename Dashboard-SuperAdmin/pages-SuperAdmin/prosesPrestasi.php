<?php
// Koneksi ke database
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'; 
$username = ''; // 'sa'; 
$password = ''; 
$database = 'PRESTASI'; 
$db; 

if ($use_driver == 'sqlsrv') { 
    $credential = [ 
        'Database' => $database, 
        'UID' => $username, 
        'PWD' => $password 
    ]; 
    
    try { 
        $db = sqlsrv_connect($host, $credential); 
        
        if (!$db) { 
            die("Connection failed: " . sqlsrv_errors()[0]['message']); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input dari form
    $judul = $_POST['judul']; // Judul kompetisi
    $nim = $_POST['nim']; 
    $peran_mahasiswa_id = $_POST['peran_mahasiswa_id']; // Ambil peran mahasiswa dari input
    $tempat = $_POST['tempat']; 
    $link_kompetisi = $_POST['link_kompetisi']; 
    $jumlah_peserta = $_POST['jumlah_peserta']; 
    $tanggal_mulai = $_POST['tanggal_mulai']; 
    $tanggal_akhir = $_POST['tanggal_akhir']; 
    $nomor_surat_tugas = $_POST['nomor_surat_tugas']; 
    $tanggal_surat_tugas = $_POST['tanggal_surat_tugas']; 
    $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
    $peringkat_id = $_POST['peringkat_id'];

    // Cek apakah Judul Kompetisi ada di tabel kategori
    $sql = "SELECT kategori_id FROM kategori WHERE nama_kategori = ?";
    $params = [$judul];
    $stmt = sqlsrv_query($db, $sql, $params);

    if ($stmt === false) {
        die("Query failed: " . print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        // Jika ada kecocokan, ambil kategori_id
        $kategori_id = $row['kategori_id'];
        header("Location: dataPrestasi.php");
    } else {
        // Jika tidak ada kecocokan, tambahkan kategori baru
        $sql_insert = "INSERT INTO kategori (nama_kategori) VALUES (?)";
        $params_insert = [$judul];
        $stmt_insert = sqlsrv_query($db, $sql_insert, $params_insert);
    
        if ($stmt_insert === false) {
            die("Insert failed: " . print_r(sqlsrv_errors(), true));
        }
    
        // Ambil kategori_id yang baru ditambahkan
        $kategori_id_query = sqlsrv_query($db, "SELECT SCOPE_IDENTITY() AS kategori_id");
        if ($kategori_id_query === false) {
            die("Query to get new kategori_id failed: " . print_r(sqlsrv_errors(), true));
        }
    
        $row_kategori_id = sqlsrv_fetch_array($kategori_id_query, SQLSRV_FETCH_ASSOC);
        if ($row_kategori_id) {
            $kategori_id = $row_kategori_id['kategori_id'];
        } else {
            die("Failed to retrieve new kategori_id.");
        }
    }     

    // Proses upload file
    $targetDir = "Prestasi/";
    $surat_tugas = $targetDir . uniqid() . '_' . basename($_FILES["surat_tugas"]["name"]);
    $sertifikat = $targetDir . uniqid() . '_' . basename($_FILES["sertifikat"]["name"]);
    $foto_kegiatan = $targetDir . uniqid() . '_' . basename($_FILES["foto_kegiatan"]["name"]);

    // Cek upload file dan simpan ke database
    if (move_uploaded_file($_FILES["surat_tugas"]["tmp_name"], $surat_tugas) &&
        move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $sertifikat) &&
        move_uploaded_file($_FILES["foto_kegiatan"]["tmp_name"], $foto_kegiatan)) {

        // Masukkan data ke tabel prestasi
        $sqlPrestasi = "INSERT INTO prestasi (judul, tempat, link_kompetisi, tanggal_mulai, tanggal_akhir, jumlah_peserta, kategori_id, tingkat_lomba_id, peringkat_id, dokumen_id, verifikasi_status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '1', 'Belum Terverifikasi')";
        $paramsPrestasi = [$judul, $tempat, $link_kompetisi, $tanggal_mulai, $tanggal_akhir, $jumlah_peserta, $kategori_id, $tingkat_lomba_id, $peringkat_id];
        $stmtPrestasi = sqlsrv_query($db, $sqlPrestasi, $paramsPrestasi);

        if ($stmtPrestasi === false) {
            die("Insert to prestasi failed: " . print_r(sqlsrv_errors(), true));
        }

        // Ambil prestasi_id yang baru ditambahkan
        $prestasi_id_query = sqlsrv_query($db, "SELECT SCOPE_IDENTITY() AS prestasi_id");
        if ($prestasi_id_query === false) {
            die("Query to get new prestasi_id failed: " . print_r(sqlsrv_errors(), true));
        }

        $row_prestasi_id = sqlsrv_fetch_array($prestasi_id_query, SQLSRV_FETCH_ASSOC);
        if ($row_prestasi_id) {
            $prestasi_id = $row_prestasi_id['prestasi_id'];

            // Cek apakah NIM ada di tabel mahasiswa
            $checkMahasiswaQuery = "SELECT * FROM mahasiswa WHERE nim = ?";
            $checkMahasiswaStmt = sqlsrv_query($db, $checkMahasiswaQuery, [$nim]);

            if ($checkMahasiswaStmt === false) {
                die("Query failed: " . print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_fetch_array($checkMahasiswaStmt, SQLSRV_FETCH_ASSOC) !== null) {
                // Jika NIM ada, masukkan ke tabel presma
                $sql_presma = "INSERT INTO presma (nim, prestasi_id, peran_mahasiswa_id) VALUES (?, ?, ?)";
                $params_presma = [$nim, $prestasi_id, $peran_mahasiswa_id];
                $stmt_presma = sqlsrv_query($db, $sql_presma, $params_presma);

                if ($stmt_presma === false) {
                    die("Insert to presma failed: " . print_r(sqlsrv_errors(), true));
                } else {
                    echo "Data berhasil dimasukkan ke tabel presma.";
                }
            } else {
                echo "NIM tidak ditemukan di tabel mahasiswa.";
            }
        } else {
            die("Failed to retrieve new prestasi_id.");
        }

        // Redirect atau tampilkan pesan sukses
        header("Location: dataPrestasi.php");
    } else {
        echo "Maaf, terjadi kesalahan saat mengupload file.";
    }
}

// Tutup koneksi
sqlsrv_close($db);
?>