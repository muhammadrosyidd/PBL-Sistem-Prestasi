<?php
require_once __DIR__ . '/../../config/Connection.php';

abstract class AbstractLogin {
    protected $conn;

    public function __construct($connection) {
        $this->conn = $connection->getConnection();
    }

    abstract public function authenticate($username, $password);
}

class Login extends AbstractLogin {
    public function authenticate($username, $password) {
        $encoded_password = md5($password); // Tetap menggunakan MD5 (TIDAK AMAN!)
        $encoded_password_bin = pack('H*', $encoded_password); // Convert MD5 hex to binary

        $sql = "SELECT password, role FROM [user] WHERE username = ?";
        $params = array(trim($username));

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            die("Query failed: " . print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            // Bandingkan password MD5 (TIDAK AMAN!)
            if ($encoded_password_bin === $row['password']) {  
                return $row['role'];
            }
        }
        return null;
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
    Log In
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

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Log In</h4>
                  <p class="mb-0">Masukkan username dan password anda </p>
                </div>
                <div class="card-body">
                  <form method="POST" action="LoginProcess.php">
                      <div class="mb-3">
                          <input type="text" name="username" class="form-control" placeholder="Username" required>
                      </div>
                      <div class="mb-3">
                          <input type="password" name="password" class="form-control" placeholder="Password" required>
                      </div>
                      <div class="text-center">
                          <button type="submit" class="btn btn-primary">Log In</button>
                      </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-success h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('../../assets2/img/pres.jpg');
          background-size: cover;">
                <span class="mask bg-gradient-warning opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Selamat Datang"</h4>
                <p class="text-white position-relative">Sistem Pencatatan Prestasi JTI POLINEMA</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
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