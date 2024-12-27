<?php
session_start();
require_once __DIR__ . '/../../config/ConnectionPDO.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['username'];

try {
  $stmt = $conn->prepare("SELECT u.*, sa.* FROM [user] u JOIN superadmin sa ON u.username = sa.username WHERE u.username = ?");
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

// Proses Update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil data dari form
  $nama = $_POST['nama'];
  $jeniskelamin = $_POST['jeniskelamin'];
  $telepon = $_POST['telepon'];
  $alamat = $_POST['alamat'];
  $jabatan = $_POST['jabatan'];
  $password = isset($_POST['password']) ? $_POST['password'] : ''; // Handle password input

  try {
    // Update data lainnya
    $stmt = $conn->prepare("UPDATE superadmin SET nama=?, jeniskelamin=?, telepon=?, alamat=?, jabatan=? WHERE username=?");
    $stmt->execute([$nama, $jeniskelamin, $telepon, $alamat, $jabatan, $username]);

    // Cek apakah password baru diisi
    if (!empty($password)) {
      // Hash password menggunakan MD5
      $encoded_password = md5($password);
      $encoded_password_bin = pack('H*', $encoded_password);

      // Update password di tabel [user]
      $update_password_query = "UPDATE [user] SET password = ? WHERE username = ?";
      $params_password = array($encoded_password_bin, $username);
      $stmt_password = $conn->prepare($update_password_query);
      $stmt_password->execute($params_password);
    }

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
    Sistem Pencatatan Prestasi
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pages-Admintyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
    <span class="mask bg-gradient-warning opacity-6"></span>
  </div>
  <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
  <div class="main-content position-relative max-height-vh-100 h-100">
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
              <form method="POST" action="" onsubmit="return confirmUpdate()">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Username</label>
                      <input class="form-control" type="text" value="<?php echo htmlspecialchars($userData['username']); ?>" readonly> <!-- Readonly untuk username -->
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Nama</label>
                      <input class="form-control" type="text" name="nama" value="<?php echo htmlspecialchars($userData['nama']); ?>">
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Jabatan</label>
                      <input class="form-control" type="text" name="jabatan" value="<?php echo htmlspecialchars($userData['jabatan']); ?>">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">Password Baru</label>
                      <input class="form-control" type="password" name="password" >
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!--   Core JS Files   -->
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
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages-Admin etc -->
    <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>