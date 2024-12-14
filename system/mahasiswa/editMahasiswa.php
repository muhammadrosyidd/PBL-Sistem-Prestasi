<?php
// Menampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include file koneksi
require_once __DIR__ . '/../../config/Connection.php';

// Initialize variables
$nim = isset($_GET['nim']) ? $_GET['nim'] : ''; // Assuming 'nim' is passed via URL or set it if needed
$nama_depan = '';
$nama_belakang = '';
$jenis_kelamin = ''; // Default to empty
$telepon = '';
$alamat = '';
$prodi_id = ''; // Default to empty
$username = ''; // Default to empty, or fetch from database if needed

// Fetch existing data for editing (assuming you are querying based on $nim)
if ($nim != '') {
    // Assuming query to fetch student details
    $query = "SELECT * FROM mahasiswa WHERE nim = ?";
    $stmt = sqlsrv_query($conn, $query, array($nim));
    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $nama_depan = $row['nama_depan'];
        $nama_belakang = $row['nama_belakang'];
        $jenis_kelamin = $row['jeniskelamin'];
        $telepon = $row['telepon'];
        $alamat = $row['alamat'];
        $prodi_id = $row['prodi_id'];

        // Also fetch the username from the 'user' table
        $query_user = "SELECT username FROM [user] WHERE username = ?";
        $stmt_user = sqlsrv_query($conn, $query_user, array($nim));
        if ($stmt_user) {
            $user_row = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);
            $username = $user_row['username'];
        }
    }
}

// Fetch program studi data from the prodi table
$query_prodi = "SELECT * FROM prodi";
$stmt_prodi = sqlsrv_query($conn, $query_prodi);
$prodi_list = [];
if ($stmt_prodi) {
    while ($row = sqlsrv_fetch_array($stmt_prodi, SQLSRV_FETCH_ASSOC)) {
        $prodi_list[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nim = trim($_POST['nim']);
    $nama_depan = trim($_POST['nama_depan']);
    $nama_belakang = trim($_POST['nama_belakang']);
    $jenis_kelamin = trim($_POST['jeniskelamin']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $prodi_id = trim($_POST['prodi_id']);
    $password = trim($_POST['password']); // Ambil password

    try {
        // Validasi NIM
        if (empty($nim)) {
            throw new Exception("NIM tidak boleh kosong.");
        }

        // Begin Transaction
        sqlsrv_begin_transaction($conn);

        // Update data di tabel mahasiswa
        if (!empty($password)) {
            // Jika password diisi, perbarui dengan hash MD5
            $query_update_mahasiswa = "
                UPDATE [mahasiswa] 
                SET nama_depan = ?, nama_belakang = ?, jeniskelamin = ?, telepon = ?, alamat = ?, prodi_id = ? 
                WHERE nim = ?";
            $params_update_mahasiswa = array($nama_depan, $nama_belakang, $jenis_kelamin, $telepon, $alamat, $prodi_id, $nim);

            $query_update_user = "
                UPDATE [user] 
                SET username = ?, password = CONVERT(VARBINARY(16), HASHBYTES('MD5', ?)) 
                WHERE username = ?";
            $params_update_user = array($nim, $password, $nim);
        } else {
            // Jika password tidak diisi, hanya perbarui data mahasiswa
            $query_update_mahasiswa = "
                UPDATE [mahasiswa] 
                SET nama_depan = ?, nama_belakang = ?, jeniskelamin = ?, telepon = ?, alamat = ?, prodi_id = ? 
                WHERE nim = ?";
            $params_update_mahasiswa = array($nama_depan, $nama_belakang, $jenis_kelamin, $telepon, $alamat, $prodi_id, $nim);

            // Query update username tanpa mengganti password
            $query_update_user = "
                UPDATE [user] 
                SET username = ? 
                WHERE username = ?";
            $params_update_user = array($nim, $nim);
        }

        // Eksekusi query update mahasiswa
        $stmt_update_mahasiswa = sqlsrv_query($conn, $query_update_mahasiswa, $params_update_mahasiswa);
        if ($stmt_update_mahasiswa === false) {
            throw new Exception("Error updating mahasiswa: " . print_r(sqlsrv_errors(), true));
        }

        // Eksekusi query update user
        $stmt_update_user = sqlsrv_query($conn, $query_update_user, $params_update_user);
        if ($stmt_update_user === false) {
            throw new Exception("Error updating user: " . print_r(sqlsrv_errors(), true));
        }

        // Commit Transaction
        sqlsrv_commit($conn);

        // Redirect ke halaman dataMahasiswa.php setelah berhasil
        header("Location: dataMahasiswa.php");
        exit();
    } catch (Exception $e) {
        // Rollback Transaction jika terjadi error
        sqlsrv_rollback($conn);

        // Tampilkan pesan error
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/jti.png">
    <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
    <title>
        Input Mahasiswa
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/tambahmhs.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="position-absolute w-100 min-height-300 top-0"
        style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-warning opacity-5"></span>
    </div>
    <?php
    include_once __DIR__ . '/../layout/sidebarSuper.php';
    ?>
    <div class="main-content position-relative max-height-vh-100 h-100">
        <!-- Navbar -->
        <nav
            class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2 mt-n11">

        </nav>
        <!-- End Navbar -->
        <div class="card shadow-lg mx-4 card-profile-bottom">

        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Mahasiswa</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <p class="text-uppercase text-sm">User Information</p> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nim" class="form-control-label">NIM Mahasiswa</label>
                                                    <input class="form-control" type="text" name="nim" value="<?php echo htmlspecialchars($nim ?? ''); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="username" class="form-control-label">Username</label>
                                                    <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-control-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                                                    <input class="form-control" type="password" name="password">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nama_depan" class="form-control-label">Nama Depan</label>
                                                    <input class="form-control" type="text" name="nama_depan" value="<?php echo htmlspecialchars($nama_depan ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nama_belakang" class="form-control-label">Nama Belakang</label>
                                                    <input class="form-control" type="text" name="nama_belakang" value="<?php echo htmlspecialchars($nama_belakang ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="jeniskelamin" class="form-control-label">Jenis Kelamin</label>
                                                    <select class="form-control" name="jeniskelamin" required>
                                                        <option value="L" <?php echo ($jenis_kelamin == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                                        <option value="P" <?php echo ($jenis_kelamin == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="telepon" class="form-control-label">No Telepon</label>
                                                    <input class="form-control" type="text" name="telepon" value="<?php echo htmlspecialchars($telepon ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="alamat" class="form-control-label">Alamat</label>
                                                    <input class="form-control" type="text" name="alamat" value="<?php echo htmlspecialchars($alamat ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="prodi_id" class="form-control-label">Program Studi</label>
                                                    <select class="form-control" name="prodi_id" required>
                                                        <option value="1" <?php echo ($prodi_id == 1) ? 'selected' : ''; ?>>D4 Teknik Informatika</option>
                                                        <option value="2" <?php echo ($prodi_id == 2) ? 'selected' : ''; ?>>D4 Sistem Informasi Bisnis</option>
                                                        <option value="3" <?php echo ($prodi_id == 3) ? 'selected' : ''; ?>>D2 Pengembangan Piranti Lunak Situs</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-warning">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--   Core JS Files   -->
    <script>
        function addRow() {
            var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);

            cell1.innerHTML = rowCount + 1;
            cell2.innerHTML = '<select><option>Pilih Mahasiswa</option></select>';
            cell3.innerHTML = '<select><option>Pilih Peran</option></select>';
            cell4.innerHTML = '<button class="btn-delete" onclick="deleteRow(this)"><i class="fas fa-times"></i></button>';
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();
        }

        function updateRowNumbers() {
            var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
            for (var i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerHTML = i + 1;
            }
        }
    </script>
    <script>
        function addRow1() {
            var table = document.getElementById("pembimbingTable").getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);

            cell1.innerHTML = rowCount + 1;
            cell2.innerHTML = '<select><option>Pilih Pembimbing</option></select>';
            cell3.innerHTML = '<select><option>Pilih Peran Pembimbing</option></select>';
            cell4.innerHTML = '<button class="btn-delete" onclick="deleteRow(this)"><i class="fas fa-times"></i></button>';
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();
        }

        function updateRowNumbers() {
            var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
            for (var i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerHTML = i + 1;
            }
        }
    </script>
    <script src="../../assets2/js/core/popper.min.js"></script>
    <script src="../../assets2/js/core/bootstrap.min.js"></script>
    <script src="../../assets2/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../assets2/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>