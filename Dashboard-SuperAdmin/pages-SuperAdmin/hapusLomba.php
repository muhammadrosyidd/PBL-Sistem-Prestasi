<?php 
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'
$linkPendaftaran = ''; // 'sa'
$password = ''; 
$database = 'PRESTASI'; 
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
    $id_infoLomba = isset($_POST['id_infoLomba']) ? $_POST['id_infoLomba'] : '';

    if (!empty($id_infoLomba)) {
        // Ambil nama file gambar dari database
        $querySelect = "SELECT gambar_poster FROM infolomba WHERE id_infoLomba = '$id_infoLomba'";
        $resultSelect = $use_driver == 'mysql' ? $db->query($querySelect) : sqlsrv_query($db, $querySelect);
        
        // Check if the query was successful
        if ($use_driver == 'sqlsrv' && $resultSelect === false) {
            $msg = sqlsrv_errors();
            die("Error in query: " . $msg[0]['message']);
        }

        if ($use_driver == 'mysql') {
            $row = $resultSelect->fetch_assoc();
            $posterFile = $row['gambar_poster'];
        } else if ($use_driver == 'sqlsrv') {
            $row = sqlsrv_fetch_array($resultSelect, SQLSRV_FETCH_ASSOC);
            if ($row === false) {
                die("Error fetching data: " . sqlsrv_errors()[0]['message']);
            }
            $posterFile = $row['gambar_poster'];
        }

        // Hapus file gambar jika ada
        if (!empty($posterFile)) {
            $filePath = 'Poster Lomba/' . $posterFile; // Sesuaikan dengan path folder Anda
            if (file_exists($filePath)) {
                unlink($filePath); // Menghapus file
            }
        }

        // SQL untuk menghapus data
        $queryDelete = "DELETE FROM infolomba WHERE id_infoLomba = '$id_infoLomba'";

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