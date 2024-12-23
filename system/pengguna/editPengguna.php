<?php
// Menampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai output buffering
ob_start();

// Include file koneksi
require_once __DIR__ . '/../../config/Connection.php';

// Fetch user data if available for editing
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $query = "SELECT * FROM [user] u 
              LEFT JOIN [superadmin] sa ON u.username = sa.username
              LEFT JOIN [admin] a ON u.username = a.username
              WHERE u.username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);
    $user_data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Populate form fields with existing data
    $nama_admin = $user_data['nama'] ?? '';
    $jabatan = $user_data['jabatan'] ?? '';
    $jenis_kelamin = $user_data['jeniskelamin'] ?? '';
    $no_telepon = $user_data['telepon'] ?? '';
    $alamat = $user_data['alamat'] ?? '';
    $role = $user_data['role'] ?? '';
}

// Handle the form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_admin = $_POST['nama_admin'];
    $jabatan = $_POST['jabatan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $username = $_POST['username']; // Username tetap sama
    $password = $_POST['password'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    // Jika password diubah, encode password ke MD5 (hexadecimal 32 karakter)
    if (!empty($password)) {
        $encoded_password = md5($password);
        $encoded_password_bin = pack('H*', $encoded_password);
        $update_password_query = "UPDATE [user] SET password = ? WHERE username = ?";
        $params_password = array($encoded_password_bin, $username);
        $stmt_password = sqlsrv_query($conn, $update_password_query, $params_password);
    }

    // Update data user (username dan role tidak berubah, jadi tidak perlu diubah di sini)
    $update_user_query = "UPDATE [user] SET role = ? WHERE username = ?";
    $params_update_user = array($role, $username);
    $stmt_update_user = sqlsrv_query($conn, $update_user_query, $params_update_user);

    // Update data superadmin atau admin berdasarkan role
    if ($role == 1) { // Super Admin
        $update_superadmin_query = "UPDATE [superadmin] SET nama = ?, jeniskelamin = ?, telepon = ?, alamat = ?, jabatan = ? WHERE username = ?";
        $params_update_superadmin = array($nama_admin, $jenis_kelamin, $no_telepon, $alamat, $jabatan, $username);
        $stmt_update_superadmin = sqlsrv_query($conn, $update_superadmin_query, $params_update_superadmin);
    } elseif ($role == 2) { // Admin
        $update_admin_query = "UPDATE [admin] SET nama = ?, jeniskelamin = ?, telepon = ?, alamat = ?, jabatan = ? WHERE username = ?";
        $params_update_admin = array($nama_admin, $jenis_kelamin, $no_telepon, $alamat, $jabatan, $username);
        $stmt_update_admin = sqlsrv_query($conn, $update_admin_query, $params_update_admin);
    }

    // Redirect ke halaman dataPengguna.php setelah berhasil
    header("Location: dataPengguna.php");
    exit(); // Pastikan script dihentikan setelah redirect
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
        Update Pengguna
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

        <!-- End Navbar -->
        <div class="card shadow-lg mx-4 card-profile-bottom">
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Update Pengguna</p>

                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nama_admin" class="form-control-label">Nama Admin</label>
                                            <input class="form-control" type="text" name="nama_admin" value="<?php echo htmlspecialchars($nama_admin ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jabatan" class="form-control-label">Jabatan</label>
                                            <input class="form-control" type="text" name="jabatan" value="<?php echo htmlspecialchars($jabatan ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jenis_kelamin" class="form-control-label">Jenis Kelamin</label>
                                            <select class="form-control" name="jenis_kelamin" required>
                                                <option value="L" <?php if ($jenis_kelamin == 'L') echo 'selected'; ?>>Laki-laki</option>
                                                <option value="P" <?php if ($jenis_kelamin == 'P') echo 'selected'; ?>>Perempuan</option>
                                            </select>
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
                                            <label for="no_telepon" class="form-control-label">No Telepon</label>
                                            <input class="form-control" type="text" name="no_telepon" value="<?php echo htmlspecialchars($no_telepon ?? ''); ?>" required>
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
                                            <label for="role" class="form-control-label">Role</label>
                                            <select class="form-control" name="role" required>
                                                <option value="1" <?php if ($role == 1) echo 'selected'; ?>>1 - Super Admin</option>
                                                <option value="2" <?php if ($role == 2) echo 'selected'; ?>>2 - Admin</option>
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