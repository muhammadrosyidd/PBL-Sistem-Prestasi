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
    $nama_admin = isset($_POST['nama_admin']) ? $_POST['nama_admin'] : '';
    $jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $telepon = isset($_POST['telepon']) ? $_POST['telepon'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';

    // Encode password ke Base64
    $encoded_password = base64_encode($password);

    // Cek jika semua data ada
    if (!empty($nama_admin) && !empty($jabatan) && !empty($jenis_kelamin) && !empty($username) && !empty($encoded_password) && !empty($telepon) && !empty($alamat) && !empty($role_id)) {
        // SQL untuk memasukkan data
        if ($use_driver == 'sqlsrv') {
            $query = "INSERT INTO [admin] (nama_admin, jabatan, jenis_kelamin, username, password, telepon, alamat, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$nama_admin, $jabatan, $jenis_kelamin, $username, $encoded_password, $telepon, $alamat, $role_id];
            $stmt = sqlsrv_query($db, $query, $params);
            if ($stmt) {
                header("Location: dataPengguna.php");
                exit();
            } else {
                $msg = sqlsrv_errors();
                echo "Error: " . $msg[0]['message'];
            }
        }        

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