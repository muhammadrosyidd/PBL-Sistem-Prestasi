<?php
// Menampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai output buffering
ob_start();

// Include file koneksi
require_once __DIR__ . '/../../config/Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nim = $_POST['nim'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_depan = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    $jenis_kelamin = $_POST['jeniskelamin'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $prodi_id = $_POST['prodi_id'];

    // Tetapkan role menjadi 3 (mahasiswa)
    $role = 3;

    try {
        // Membuat koneksi baru
        $db = new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI");
        $conn = $db->connect();

        // Mulai transaksi
        sqlsrv_begin_transaction($conn);

        // Encode password ke MD5 (hexadecimal 32 karakter)
        $encoded_password = md5($password);

        // Ubah MD5 hex ke binary untuk tipe VARBINARY di database
        $encoded_password_bin = pack('H*', $encoded_password);

        // Query untuk memasukkan data ke tabel user
        $query_user = "INSERT INTO [user] (username, password, role) 
                   VALUES (?, HASHBYTES('MD5', ?), ?)";
        $params_user = array($username, $encoded_password_bin, $role);

        // Eksekusi query untuk tabel user
        $stmt_user = sqlsrv_query($conn, $query_user, $params_user);
        if ($stmt_user === false) {
            throw new Exception("Error inserting user: " . print_r(sqlsrv_errors(), true));
        }

        // Query untuk memasukkan data ke tabel mahasiswa
        $query_mahasiswa = "INSERT INTO [mahasiswa] (nim, username, nama_depan, nama_belakang, jeniskelamin, telepon, alamat, prodi_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params_mahasiswa = array($nim, $username, $nama_depan, $nama_belakang, $jenis_kelamin, $telepon, $alamat, $prodi_id);

        $stmt_mahasiswa = sqlsrv_query($conn, $query_mahasiswa, $params_mahasiswa);
        if ($stmt_mahasiswa === false) {
            throw new Exception("Error inserting mahasiswa: " . print_r(sqlsrv_errors(), true));
        }

        // Commit transaksi jika semua query berhasil
        sqlsrv_commit($conn);

        // Redirect ke halaman dataMahasiswa.php setelah berhasil
        header("Location: dataMahasiswa.php");
        exit();
    } catch (Exception $e) {
        // Rollback jika terjadi error
        if (isset($conn)) {
            sqlsrv_rollback($conn);
        }

        // Tampilkan pesan error
        echo "Error: " . $e->getMessage();
    } finally {
        // Pastikan koneksi ditutup
        if (isset($db)) {
            $db->close();
        }
    }
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
          <a class="nav-link " href="../pages-SuperAdmin/dataPengguna.php">
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
          <a class="nav-link active" href="../pages-SuperAdmin/dataMahasiswa.php">
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
                <p class="mb-0">Input Mahasiswa</p>
              </div>
            </div>
            <div class="card-body">
              <!-- <p class="text-uppercase text-sm">User Information</p> -->
              <div class="row">
                <div class="col-md-12">
                  <form action="" method="POST">
                    <div class="form-group">
                      <label for="nim">NIM Mahasiswa</label>
                      <input class="form-control" type="text" name="nim" required>
                    </div>
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input class="form-control" type="text" name="username" required>
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input class="form-control" type="text" name="password" required>
                    </div>
                    <div class="form-group">
                      <label for="nama_depan">Nama Depan</label>
                      <input class="form-control" type="text" name="nama_depan" required>
                    </div>
                    <div class="form-group">
                      <label for="nama_belakang">Nama Belakang</label>
                      <input class="form-control" type="text" name="nama_belakang" required>
                    </div>
                    <div class="form-group">
                      <label for="jeniskelamin">Jenis Kelamin</label>
                      <select class="form-control" name="jeniskelamin" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="telepon">No Telepon</label>
                      <input class="form-control" type="number" name="telepon" required>
                    </div>
                    <div class="form-group">
                      <label for="alamat">Alamat</label>
                      <input class="form-control" type="text" name="alamat" required>
                    </div>
                    <div class="form-group">
                      <label for="prodi_id">Program Studi</label>
                      <select class="form-control" name="prodi_id" required>
                        <option value="1">D4 Teknik Informatika</option>
                        <option value="2">D4 Sistem Informasi Bisnis</option>
                        <option value="3">D2 Pengembangan Piranti Lunak Situs</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm">Submit</button>
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