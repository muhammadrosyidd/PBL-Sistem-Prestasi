<?php 
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'
$username = ''; // 'sa'
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
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';

    if (!empty($nim)) {
        // SQL untuk menghapus data
        $query = "DELETE FROM mahasiswa WHERE nim = '$nim'";

        // Eksekusi query sesuai driver
        if ($use_driver == 'mysql') {
            if ($db->query($query) === TRUE) {
                header("Location: dataMahasiswa.php");
                exit();
            } else {
                echo "Error: " . $db->error;
            }
        } else if ($use_driver == 'sqlsrv') {
            $stmt = sqlsrv_query($db, $query);
            if ($stmt) {
                header("Location: dataMahasiswa.php");
                exit();
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        }
    } else {
        echo "NIM tidak ditemukan.";
    }
}

// Menutup koneksi
if ($use_driver == 'mysql') {
    $db->close();
} else if ($use_driver == 'sqlsrv') {
    sqlsrv_close($db);
}
?>