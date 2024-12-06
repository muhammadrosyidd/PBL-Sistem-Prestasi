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
    $nidn = isset($_POST['nidn']) ? $_POST['nidn'] : '';
    $nama_dosen = isset($_POST['nama_dosen']) ? $_POST['nama_dosen'] : '';
    $telepon = isset($_POST['telepon']) ? $_POST['telepon'] : '';

    // Cek jika semua data ada
    if (!empty($nidn) && !empty($nama_dosen) && !empty($telepon)) {
        // SQL untuk memasukkan data
        $query = "INSERT INTO dosen (nidn, nama_dosen, telepon) VALUES ('$nidn', '$nama_dosen', '$telepon')";

        // Eksekusi query sesuai driver
        if ($use_driver == 'mysql') {
            if ($db->query($query) === TRUE) {
                header("Location: dataDosen.php");
            } else {
                echo "Error: " . $query . "<br>" . $db->error;
            }
        } else if ($use_driver == 'sqlsrv') {
            $stmt = sqlsrv_query($db, $query);
            if ($stmt) {
                header("Location: dataDosen.php");
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