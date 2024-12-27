<?php
session_start();
require_once __DIR__ . '/../../config/ConnectionPDO.php';

if (!isset($_SESSION['username'])) {
    header("Location: /PBL-Sistem-Prestasi/system/pages-Sign-in/Login.php");
    exit();
}

$username = $_SESSION['username'];

try {
    $stmt = $conn->prepare("SELECT nama FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $namaPengguna = $userData ? $userData['nama'] : "Pengguna";
} catch (PDOException $e) {
    $namaPengguna = "Pengguna";
    error_log("Error fetching user data: " . $e->getMessage());
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
    Dashboard - Pencatatan Prestasi
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <!-- <h1>Dashboard Prestasi</h1> -->

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
  <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
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
                Admin</a>
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
                href="../profile/profileAdmin.php"
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
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Jumlah Mahasiswa</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $jumlahMahasiswa; ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Mahasiswa Berprestasi</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $jumlahMapres; ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Prestasi Telah Divalidasi</p>
                    <h5 class="font-weight-bolder">
                    <?php echo $jumlahVerif; ?>
                    </h5>
                    <!-- <p class="mb-0">
                      <span class="text-danger text-sm font-weight-bolder">-2%</span>
                      since last quarter
                    </p> -->
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Prestasi Belum Divalidasi</p>
                    <h5 class="font-weight-bolder">
                    <?php echo $jumlahNonVerif; ?>
                    </h5>
                    <!-- <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">+5%</span> than last month
                    </p> -->
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
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
        <div class="col-lg-5">
          <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
              <div class="carousel-inner border-radius-lg h-100">
                <div class="carousel-item h-100 active" style="background-image: url('../../assets2/img/carousel-1.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-camera-compact text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Get started with Argon</h5>
                    <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../../assets2/img/carousel-2.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Faster way to create web pages</h5>
                    <p>That’s my skill. I’m not really specifically talented at anything except for the ability to
                      learn.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../../assets2/img/carousel-3.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-trophy text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Share with us your design tips!</h5>
                    <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
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