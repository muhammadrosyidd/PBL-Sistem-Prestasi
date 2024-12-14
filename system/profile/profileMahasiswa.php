<?php
require_once __DIR__ . '/../../config/Connection.php';

// Periksa apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil data dari form
  $nim = $_POST["nim"];
  $username = $_POST["username"];
  $namaDepan = $_POST["namaDepan"];
  $namaBelakang = $_POST["namaBelakang"];
  $passwordLama = $_POST["passwordLama"];
  $passwordBaru = $_POST["passwordBaru"];
  $jenisKelamin = $_POST["jeniskelamin"];
  $noTelepon = $_POST["noTelepon"];
  $alamat = $_POST["alamat"];
  $prodi = $_POST["prodi"];
  $angkatan = $_POST["angkatan"];

  // Validasi data (tambahkan validasi sesuai kebutuhan)
  if (empty($namaDepan) || empty($namaBelakang) || empty($passwordBaru) || empty($jenisKelamin) || empty($noTelepon) || empty($alamat) || empty($prodi) || empty($angkatan)) {
    echo "Semua field harus diisi.";
    exit; // Hentikan eksekusi script jika ada field yang kosong
  }

  // Enkripsi password baru
  $passwordBaru = password_hash($passwordBaru, PASSWORD_DEFAULT);

  // Ambil password lama dari database
  $sql = "SELECT password FROM mahasiswa WHERE nim = ?";
  $params = array($nim);
  $stmt = sqlsrv_query($conn, $sql, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $passwordLamaDatabase = $row["password"];

    // Verifikasi password lama
    if (!password_verify($passwordLama, $passwordLamaDatabase)) {
      echo "Password lama salah.";
      exit;
    } else {
      // Update data profile
      $sql = "UPDATE mahasiswa SET 
                nama_depan = ?,
                nama_belakang = ?,
                password = ?,
                jenis_kelamin = ?,
                no_telepon = ?,
                alamat = ?,
                prodi = ?,
                angkatan = ?
              WHERE nim = ?";
      $params = array($namaDepan, $namaBelakang, $passwordBaru, $jenisKelamin, $noTelepon, $alamat, $prodi, $angkatan, $nim);
      $stmt = sqlsrv_query($conn, $sql, $params);

      if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
      } else {
        echo "<script>alert('Profile berhasil diupdate.'); window.location.href = 'dashboard.php';</script>";
        exit;
      }
    }
  } else {
    echo "NIM tidak ditemukan.";
    exit;
  }
}

// Tutup koneksi
sqlsrv_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
  <title>
    Profile
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
    <span class="mask bg-gradient-warning opacity-6"></span>
  </div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages-Mahasiswa/dashboard.html "
        target="_blank">
        <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " href="../pages-Mahasiswa/dashboard.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link " href="../pages-Mahasiswa/dataPrestasi.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Prestasi</span>
          </a>
        </li>


      </ul>
    </div>
  </aside>
  <div class="main-content position-relative max-height-vh-100 h-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2 mt-n11">

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
                <p class="mb-0">Edit Profile</p>

              </div>
            </div>
            <div class="card-body">
              <p class="text-uppercase text-sm">Profil Mahasiswa</p>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">NIM Mahasiswa</label>
                    <input class="form-control" type="text" disabled>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="username" class="form-control-label">Username</label>
                      <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" readonly required>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Nama Depan</label>
                    <input class="form-control" type="text">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Nama Belakang</label>
                    <input class="form-control" type="text">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Password lama</label>
                    <input class="form-control" type="text">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Password baru</label>
                    <input class="form-control" type="email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Jenis Kelamin</label>
                    <select class="form-control" name="jeniskelamin" id="jeniskelamin">
                      <option value="0">Pilih Jenis Kelamin</option>
                      <option value="1" checked>Laki-laki</option>
                      <option value="2">Perempuan</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">No Telepon</label>
                    <input class="form-control" type="text">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Alamat</label>
                    <input class="form-control" type="text">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Program Studi</label>
                    <select class="form-control" name="prodi" id="prodi">
                      <option value="0">Pilih Program Studi</option>
                      <option value="1">D4 Teknik Informatika</option>
                      <option value="2">D4 Sistem Informasi Bisnis</option>
                      <option value="3">D2 Pengembangan Piranti Lunak Situs</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Angkatan</label>
                    <input class="form-control" type="text">
                  </div>

                </div>

              </div>
              <button class="btn bg-gradient-warning btn-sm ">Simpan</button>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <!--   Core JS Files   -->
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