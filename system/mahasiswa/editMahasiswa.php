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
              LEFT JOIN [mahasiswa] m ON u.username = m.username
              WHERE u.username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);
    $user_data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Populate form fields with existing data
    $nim = $user_data['nim'];
    $nama_depan = $user_data['nama_depan'];
    $nama_belakang = $user_data['nama_belakang'];
    $jenis_kelamin = $user_data['jeniskelamin'];
    $jabatan = $user_data['jabatan'];
    $telepon = $user_data['telepon'];
    $alamat = $user_data['alamat'];
    
}

// Handle the form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nim = $_POST['nim'];
    $nama_depan = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    // $jenis_kelamin = $_POST['jeniskelamin'];
    $jabatan = $_POST['jabatan'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    
    // Convert 'L' and 'P' back to 1 and 2 for database storage
    $jenis_kelamin_db = ($jenis_kelamin == 'L') ? 1 : 2;

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
    if ($role == 3) { // Super Admin
        $update_mahasiswa_query = "UPDATE [mahasiswa] SET nim = ?, nama_depan =?, nama_belakang = ?, prodi_id = ?, jeniskelamin = ?, telepon = ?, alamat = ? WHERE username = ?";
        $params_update_mahasiswa = array($nim, $nama_depan, $nama_belakang, $jenis_kelamin, $telepon, $alamat, $prodi_id, $username);
        $stmt_update_mahasiswa = sqlsrv_query($conn, $update_mahasiswa_query, $params_update_mahasiswa);
    } 

    // Redirect ke halaman dataPengguna.php setelah berhasil
    header("Location: dataMahasiswa.php");
    exit(); // Pastikan script dihentikan setelah redirect
}

// Menyelesaikan output buffering
ob_end_flush();
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
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html "
                target="_blank">
                <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="../pages-SuperAdmin/dashboard.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../pages-SuperAdmin/dataPengguna.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages-SuperAdmin/dataDosen.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Dosen</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages-SuperAdmin/dataMahasiswa.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Mahasiswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages-SuperAdmin/dataPrestasi.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Prestasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="informasiLomba.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Informasi Lomba</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="laporan.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Laporan</span>
                    </a>
                </li>

            </ul>
        </div>

    </aside>
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
                                    <label for="nim" class="form-control-label">NIM Mahasiswa</label>
                                    <input class="form-control" type="text" name="nim" value="<?php echo $nim; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="username" class="form-control-label">Username</label>
                                    <input class="form-control" type="text" name="username" value="<?php echo $username; ?>" required>
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
                                    <input class="form-control" type="text" name="nama_depan" value="<?php echo $nama_depan; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_belakang" class="form-control-label">Nama Belakang</label>
                                    <input class="form-control" type="text" name="nama_belakang" value="<?php echo $nama_belakang; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="prodi_id" class="form-control-label">Program Studi</label>
                                    <select class="form-control" name="prodi_id" required>
                                        <option value="1" <?php if ($prodi_id == '1') echo 'selected'; ?>>D4 Teknik Informatika</option>
                                        <option value="2" <?php if ($prodi_id == '2') echo 'selected'; ?>>D4 Sistem Informasi Bisnis</option>
                                        <option value="3" <?php if ($prodi_id == '3') echo 'selected'; ?>>D2 Pengembangan Piranti Lunak Situs</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="jeniskelamin" class="form-control-label">Jenis Kelamin</label>
                                    <select class="form-control" name="jeniskelamin" required>
                                        <option value="L" <?php if ($jeniskelamin == 'L') echo 'selected'; ?>>Laki-laki</option>
                                        <option value="P" <?php if ($jeniskelamin == 'P') echo 'selected'; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="telepon" class="form-control-label">No Telepon</label>
                                    <input class="form-control" type="number" name="telepon" value="<?php echo $telepon; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat" class="form-control-label">Alamat</label>
                                    <input class="form-control" type="text" name="alamat" value="<?php echo $alamat; ?>" readonly required>
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