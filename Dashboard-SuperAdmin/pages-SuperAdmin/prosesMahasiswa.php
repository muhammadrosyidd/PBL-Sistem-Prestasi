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
    // Ambil data dari form
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $program_studi = isset($_POST['program_studi']) ? $_POST['program_studi'] : '';
    $angkatan = isset($_POST['angkatan']) ? $_POST['angkatan'] : '';

    // Cek jika semua data ada
    if (!empty($nim) && !empty($username) && !empty($nama) && !empty($jenis_kelamin) && !empty($program_studi) && !empty($angkatan)) {
        // SQL untuk memasukkan data
        $query = "INSERT INTO mahasiswa (nim, username, nama_mahasiswa, jenis_kelamin, program_studi, angkatan) VALUES ('$nim', '$username', '$nama', '$jenis_kelamin', '$program_studi', $angkatan)";

        // Eksekusi query sesuai driver
        if ($use_driver == 'mysql') {
            if ($db->query($query) === TRUE) {
                header("Location: dataMahasiswa.php");
            } else {
                echo "Error: " . $query . "<br>" . $db->error;
            }
        } else if ($use_driver == 'sqlsrv') {
            $stmt = sqlsrv_query($db, $query);
            if ($stmt) {
                header("Location: dataMahasiswa.php");
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        }
    } else {
        echo "Semua field harus diisi.";
    }
} else {
    echo "Tidak ada data yang dikirim.";
}

// Menutup koneksi
if ($use_driver == 'mysql') {
    $db->close();
} else if ($use_driver == 'sqlsrv') {
    sqlsrv_close($db);
}
?>