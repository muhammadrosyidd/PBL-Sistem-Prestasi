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
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';

    // Encode password ke Base64
    $encoded_password = base64_encode($password);

    // Cek jika semua data ada
    if (!empty($username) && !empty($encoded_password) && !empty($role_id)) {
        // SQL untuk memasukkan data
        $query = "INSERT INTO [user] (username, password, role_id) VALUES ('$username', '$encoded_password', '$role_id')";

        // Eksekusi query sesuai driver
        if ($use_driver == 'mysql') {
            if ($db->query($query) === TRUE) {
                header("Location: dataPengguna.php");
            } else {
                echo "Error: " . $query . "<br>" . $db->error;
            }
        } else if ($use_driver == 'sqlsrv') {
            $stmt = sqlsrv_query($db, $query);
            if ($stmt) {
                header("Location: dataPengguna.php");
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