<?php
session_start();
require_once __DIR__ . '/../../config/ConnectionPDO.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['username'];

try {
  $stmt = $conn->prepare("SELECT u.*, sa.* FROM [user] u JOIN mahasiswa sa ON u.username = sa.username WHERE u.username = ?");
  $stmt->execute([$username]);
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$userData) {
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit();
  }
} catch (PDOException $e) {
  die("Error fetching user data: " . $e->getMessage());
}

//Proses Update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil data dari form
  $nama_depan = $_POST['nama_depan'];
  $nama_belakang = $_POST['nama_belakang'];
  $jeniskelamin = $_POST['jeniskelamin'];
  $telepon = $_POST['telepon'];
  $alamat = $_POST['alamat'];
  $prodi_id = $_POST['prodi_id'];

  try {
    $stmt = $conn->prepare("UPDATE mahasiswa SET nama_depan=?, nama_belakang=?, jeniskelamin=?, telepon=?, alamat=?, prodi_id=? WHERE username=?");
    $stmt->execute([$nama_depan, $nama_belakang, $jeniskelamin, $telepon, $alamat, $prodi_id, $username]);

    echo "<p id='success-message' style='color:green;'>Profil berhasil diperbarui!</p>";
    echo "<script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
            }, 1000); // Pesan akan hilang setelah 1 detik
          </script>";
  } catch (PDOException $e) {
    echo "<p style='color:red;'>Error updating profile: " . $e->getMessage() . "</p>";
  }
}

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
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pageMahasiswa/dashboard.html "
        target="_blank">
        <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pageMahasiswa/dashboard.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pageMahasiswa/dataPrestasi.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Prestasi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link "  href="../logout/logout.php" onclick="return confirmLogout()">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-send text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <img class="w-50 mx-auto" src="../../assets2/img/illustrations/icon-documentation.svg" alt="sidebar_illustration">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
            <h6 class="mb-0">Need help?</h6>
            <p class="text-xs font-weight-bold mb-0">Please check our docs</p>
          </div>
        </div>
      </div>
      <a href="https://www.creative-tim.com/learning-lab/bootstrap/license/argon-dashboard" target="_blank" class="btn btn-dark btn-sm w-100 mb-3">Documentation</a>
      <a class="btn btn-primary btn-sm mb-0 w-100" href="https://www.creative-tim.com/product/argon-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
    </div> -->
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
              <form method="POST" action="" onsubmit="return confirmUpdate()">
                <input type="hidden" name="nim" value="<?php echo htmlspecialchars($userData['nim']); ?>">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nim">NIM Mahasiswa</label>
                      <input class="form-control" type="text" name="nim" value="<?php echo htmlspecialchars($userData['nim']); ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" readonly>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Nama Depan</label>
                      <input class="form-control" type="text" name="nama_depan" value="<?php echo htmlspecialchars($userData['nama_depan']); ?>">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Nama Belakang</label>
                      <input class="form-control" type="text" name="nama_belakang" value="<?php echo htmlspecialchars($userData['nama_belakang']); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="jeniskelamin" class="form-control-label">Jenis Kelamin</label>
                    <select class="form-control" name="jeniskelamin" required>
                      <option value="L" <?php echo ($userData['jeniskelamin'] === 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                      <option value="P" <?php echo ($userData['jeniskelamin'] === 'P') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Telepon</label>
                      <input class="form-control" type="text" name="telepon" value="<?php echo htmlspecialchars($userData['telepon']); ?>">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Alamat</label>
                      <input class="form-control" type="text" name="alamat" value="<?php echo htmlspecialchars($userData['alamat']); ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jeniskelamin" class="form-control-label">Program Studi</label>
                  <select class="form-control" name="prodi_id" required>
                    <option value="1" <?php echo ($userData['prodi_id'] === '1') ? 'selected' : ''; ?>>D4 Teknik Informatika</option>
                    <option value="2" <?php echo ($userData['prodi_id'] === '2') ? 'selected' : ''; ?>>D4 Sistem Informasi Bisnis</option>
                    <option value="3" <?php echo ($userData['prodi_id'] === '3') ? 'selected' : ''; ?>>D2 Pengembangan Piranti Lunak Situs</option>
                  </select>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="passwordBaru">Password Baru</label>
                    <input class="form-control" type="password" name="passwordBaru">
                  </div>
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn bg-gradient-warning btn-sm">Simpan Perubahan</button>
                </div>
            </div>
            </form>
          </div>
        </div>
      </div>

    </div>

  </div>
  </div>

  <!--   Core JS Files   -->
  <script>
    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }
</script>
  <script>
    function confirmUpdate() {
      return confirm("Apakah Anda yakin ingin menyimpan perubahan?");
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