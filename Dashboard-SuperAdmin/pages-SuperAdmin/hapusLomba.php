<?php 
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'
$linkPendaftaran = ''; // 'sa'
$password = ''; 
$database = 'PencatatanPrestasi'; 
$db; 

if ($use_driver == 'mysql') { 
    try { 
        $db = new mysqli('localhost', $linkPendaftaran, $password, $database); 
        
        if ($db->connect_error) { 
            die('Connection DB failed: ' . $db->connect_error); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
} else if ($use_driver == 'sqlsrv') { 
    $credential = [ 
        'Database' => $database, 
        'UID' => $linkPendaftaran, 
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
    $linkPendaftaran = isset($_POST['linkPendaftaran']) ? $_POST['linkPendaftaran'] : '';

    if (!empty($linkPendaftaran)) {
        // Ambil nama file gambar dari database
        $querySelect = "SELECT posterLomba FROM informasiLomba WHERE linkPendaftaran = '$linkPendaftaran'";
        $resultSelect = $use_driver == 'mysql' ? $db->query($querySelect) : sqlsrv_query($db, $querySelect);
        
        // Check if the query was successful
        if ($use_driver == 'sqlsrv' && $resultSelect === false) {
            $msg = sqlsrv_errors();
            die("Error in query: " . $msg[0]['message']);
        }

        if ($use_driver == 'mysql') {
            $row = $resultSelect->fetch_assoc();
            $posterFile = $row['posterLomba'];
        } else if ($use_driver == 'sqlsrv') {
            $row = sqlsrv_fetch_array($resultSelect, SQLSRV_FETCH_ASSOC);
            if ($row === false) {
                die("Error fetching data: " . sqlsrv_errors()[0]['message']);
            }
            $posterFile = $row['posterLomba'];
        }

        // Hapus file gambar jika ada
        if (!empty($posterFile)) {
            $filePath = 'Poster Lomba/' . $posterFile; // Sesuaikan dengan path folder Anda
            if (file_exists($filePath)) {
                unlink($filePath); // Menghapus file
            }
        }

        // SQL untuk menghapus data
        $queryDelete = "DELETE FROM informasiLomba WHERE linkPendaftaran = '$linkPendaftaran'";

        // Eksekusi query sesuai driver
        if ($use_driver == 'mysql') {
            if ($db->query($queryDelete) === TRUE) {
                header("Location: informasiLomba.php");
                exit();
            } else {
                echo "Error: " . $db->error;
            }
        } else if ($use_driver == 'sqlsrv') {
            $stmt = sqlsrv_query($db, $queryDelete);
            if ($stmt) {
                header("Location: informasiLomba.php");
                exit();
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        }
    } else {
        echo "Lomba tidak ditemukan.";
    }
}

// Menutup koneksi
if ($use_driver == 'mysql') {
    $db->close();
} else if ($use_driver == 'sqlsrv') {
    sqlsrv_close($db);
}
?>