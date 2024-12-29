<?php
session_start();
require_once __DIR__ . '/../../config/ConnectionPDO.php';

if (!isset($_SESSION['username'])) {
    header("Location: /PBL-Sistem-Prestasi/system/pages-Sign-in/Login.php");
    exit();
}

$username = $_SESSION['username'];

try {
    $stmt = $conn->prepare("SELECT nim FROM mahasiswa WHERE username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $nim = $userData ? $userData['nim'] : "Pengguna";
} catch (PDOException $e) {
    $nim = "Pengguna";
    error_log("Error fetching user data: " . $e->getMessage());
}

?>

<!-- ... (HTML form untuk login) ... -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link
    rel="apple-touch-icon"
    sizes="76x76"
    href="../../assets2/img/jti.png" />
  <link rel="icon" type="image/png" href="../../assets2/img/jti.png" />
  <title>Dashboard - Pencatatan Prestasi</title>
  <!--     Fonts and icons     -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
    rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link
    href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css"
    rel="stylesheet" />
  <link
    href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css"
    rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script
    src="https://kit.fontawesome.com/42d5adcbca.js"
    crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link
    id="pagestyle"
    href="../../assets2/css/argon-dashboard.css?v=2.1.0"
    rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
  <!-- <h1>Dashboard Prestasi</h1> -->

  <!-- Cek jika ada pesan sukses -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
  <script type="text/javascript">
    alert("Profile berhasil diperbarui!");
  </script>
  <?php endif; ?>

  <?php
  // Koneksi ke database (ganti dengan detail koneksi Anda)
  require_once __DIR__ . '/../../config/ConnectionPDO.php';
  //Query ambil jumlah mhs
  $query = "SELECT COUNT(*) AS total_mahasiswa FROM mahasiswa"; // Ganti 'mahasiswa' dengan nama tabel Anda
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $jumlahMahasiswa = $stmt->fetchColumn();

  // Query untuk menghitung mahasiswa berprestasi unik (menggunakan DISTINCT)
  $query = "SELECT COUNT(DISTINCT nim) AS jumlah_mapres FROM presma";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahMapres = $stmt2->fetchColumn();

  // Query untuk jumlah prestasi yang sudah diverifikasi
  $query = "SELECT COUNT(*) AS jumlah_verif FROM prestasi WHERE verifikasi_status = 'Terverifikasi'";
  $stmt3 = $conn->prepare($query);
  $stmt3->execute();
  $jumlahVerif = $stmt3->fetchColumn();

  // Query untuk jumlah prestasi yang belum diverifikasi
  $query = "SELECT COUNT(*) AS jumlah_verif FROM prestasi WHERE verifikasi_status = 'Belum Terverifikasi'";
  $stmt4 = $conn->prepare($query);
  $stmt4->execute();
  $jumlahNonVerif = $stmt4->fetchColumn();

  // Query untuk mengambil data prestasi selama 12 bulan terakhir
  $tahunSekarang = date('Y');
  $dataGrafik = [];
  $labelGrafik = [];

  for ($i = 0; $i < 12; $i++) {
    $bulan = date('m', strtotime("-$i months"));
    $namaBulan = date('M', strtotime("-$i months"));
    $tahun = date('Y', strtotime("-$i months"));

    $query = "SELECT COUNT(*) AS jumlah_prestasi FROM prestasi WHERE MONTH(tanggal_input) = ? AND YEAR(tanggal_input) = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$bulan, $tahun]);
    $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlahPrestasi = $hasil['jumlah_prestasi'];

    $dataGrafik[] = $jumlahPrestasi;
    $labelGrafik[] = $namaBulan . " " . $tahun;
  }

  $dataGrafik = array_reverse($dataGrafik);
  $labelGrafik = array_reverse($labelGrafik);

  $dataGrafikJson = json_encode($dataGrafik);
  $labelGrafikJson = json_encode($labelGrafik);
  ?>
  <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
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
          <a class="nav-link " href="./dataPrestasi.php">
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
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol
            class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-white" href="javascript:;">
               Mahasiswa</a>
            </li>
            <li
              class="breadcrumb-item text-sm text-white active"
              aria-current="page">
              Dashboard
            </li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
        </nav>
        <div class="mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <!-- <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Type here..."> -->
            </div>
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a
                href="../profile/profileMahasiswa.php"
                class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Profile</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a
                href="javascript:;"
                class="nav-link text-white p-0"
                id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>

          </ul>
        </div>
      </div>

    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      
      <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Grafik Prestasi</h6>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../../assets2/js/core/popper.min.js"></script>
  <script src="../../assets2/js/core/bootstrap.min.js"></script>
  <script src="../../assets2/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets2/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets2/js/plugins/chartjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

    new Chart(ctx1, {
      type: "line",
      data: {
        labels: <?php echo $labelGrafikJson; ?>,
        datasets: [{
          label: "Jumlah Prestasi",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke1,
          borderWidth: 3,
          fill: true,
          data: <?php echo $dataGrafikJson; ?>,
          maxBarThickness: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#fbfbfb',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#ccc',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    const logoutLink = document.getElementById('logout-link');

    logoutLink.addEventListener('click', function(event) {
      event.preventDefault(); // Mencegah link default

      if (confirm("Apakah Anda yakin ingin logout?")) {
        // Jika user mengkonfirmasi, arahkan ke logout.php
        window.location.href = this.href;
      }
    });
  </script>
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
  <script src="../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>