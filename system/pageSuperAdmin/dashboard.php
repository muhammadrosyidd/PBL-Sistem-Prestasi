
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="../../assets2/img/jti.png"
    />
    <link rel="icon" type="image/png" href="../../assets2/img/jti.png" />
    <title>Dashboard - Pencatatan Prestasi</title>
    <!--     Fonts and icons     -->
    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
      rel="stylesheet"
    />
    <!-- Nucleo Icons -->
    <link
      href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css"
      rel="stylesheet"
    />
    <link
      href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css"
      rel="stylesheet"
    />
    <!-- Font Awesome Icons -->
    <script
      src="https://kit.fontawesome.com/42d5adcbca.js"
      crossorigin="anonymous"
    ></script>
    <!-- CSS Files -->
    <link
      id="pagestyle"
      href="../../assets2/css/argon-dashboard.css?v=2.1.0"
      rel="stylesheet"
    />
  </head>

  <body class="g-sidenav-show bg-gray-100">
    <div
      class="min-height-300 bg-gradient-warning position-absolute w-100"
    ></div>
    <?php
    include_once __DIR__ . '/../layout/sidebarSuper.php';
    ?>
    <main class="main-content position-relative border-radius-lg">
      <!-- Navbar -->
      <nav
        class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
        id="navbarBlur"
        data-scroll="false"
      >
        <div class="container-fluid py-1 px-3">
          <nav aria-label="breadcrumb">
            <ol
              class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5"
            >
              <li class="breadcrumb-item text-sm">
                <a class="opacity-5 text-white" href="javascript:;">
                  Super Admin</a
                >
              </li>
              <li
                class="breadcrumb-item text-sm text-white active"
                aria-current="page"
              >
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
                  href="../pages-SuperAdmin/profile.html"
                  class="nav-link text-white font-weight-bold px-0"
                >
                  <i class="fa fa-user me-sm-1"></i>
                  <span class="d-sm-inline d-none">Profile</span>
                </a>
              </li>
              <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a
                  href="javascript:;"
                  class="nav-link text-white p-0"
                  id="iconNavbarSidenav"
                >
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
                      <p class="text-sm mb-0 text-uppercase font-weight-bold">
                        Jumlah Mahasiswa
                      </p>
                      <h5 class="font-weight-bolder">2500</h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div
                      class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle"
                    >
                      <i
                        class="ni ni-money-coins text-lg opacity-10"
                        aria-hidden="true"
                      ></i>
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
                      <p class="text-sm mb-0 text-uppercase font-weight-bold">
                        Mahasiswa Berprestasi
                      </p>
                      <h5 class="font-weight-bolder">120</h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div
                      class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle"
                    >
                      <i
                        class="ni ni-world text-lg opacity-10"
                        aria-hidden="true"
                      ></i>
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
                      <p class="text-sm mb-0 text-uppercase font-weight-bold">
                        Prestasi Telah Divalidasi
                      </p>
                      <h5 class="font-weight-bolder">100</h5>
                      <!-- <p class="mb-0">
                      <span class="text-danger text-sm font-weight-bolder">-2%</span>
                      since last quarter
                    </p> -->
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div
                      class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle"
                    >
                      <i
                        class="ni ni-paper-diploma text-lg opacity-10"
                        aria-hidden="true"
                      ></i>
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
                      <p class="text-sm mb-0 text-uppercase font-weight-bold">
                        Prestasi Belum Divalidasi
                      </p>
                      <h5 class="font-weight-bolder">20</h5>
                      <!-- <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">+5%</span> than last month
                    </p> -->
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div
                      class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle"
                    >
                      <i
                        class="ni ni-cart text-lg opacity-10"
                        aria-hidden="true"
                      ></i>
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
                <p class="text-sm mb-0">
                  <i class="fa fa-arrow-up text-success"></i>
                  <span class="font-weight-bold">4% more</span> in 2021
                </p>
              </div>
              <div class="card-body p-3">
                <div class="chart">
                  <canvas
                    id="chart-line"
                    class="chart-canvas"
                    height="300"
                  ></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card card-carousel overflow-hidden h-100 p-0">
              <div
                id="carouselExampleCaptions"
                class="carousel slide h-100"
                data-bs-ride="carousel"
              >
                <div class="carousel-inner border-radius-lg h-100">
                  <div
                    class="carousel-item h-100 active"
                    style="
                      background-image: url('../../assets2/img/carousel-1.jpg');
                      background-size: cover;
                    "
                  >
                    <div
                      class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
                    >
                      <div
                        class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
                      >
                        <i
                          class="ni ni-camera-compact text-dark opacity-10"
                        ></i>
                      </div>
                      <h5 class="text-white mb-1">Get started with Argon</h5>
                      <p>
                        There’s nothing I really wanted to do in life that I
                        wasn’t able to get good at.
                      </p>
                    </div>
                  </div>
                  <div
                    class="carousel-item h-100"
                    style="
                      background-image: url('../../assets2/img/carousel-2.jpg');
                      background-size: cover;
                    "
                  >
                    <div
                      class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
                    >
                      <div
                        class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
                      >
                        <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                      </div>
                      <h5 class="text-white mb-1">
                        Faster way to create web pages
                      </h5>
                      <p>
                        That’s my skill. I’m not really specifically talented at
                        anything except for the ability to learn.
                      </p>
                    </div>
                  </div>
                  <div
                    class="carousel-item h-100"
                    style="
                      background-image: url('../../assets2/img/carousel-3.jpg');
                      background-size: cover;
                    "
                  >
                    <div
                      class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
                    >
                      <div
                        class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
                      >
                        <i class="ni ni-trophy text-dark opacity-10"></i>
                      </div>
                      <h5 class="text-white mb-1">
                        Share with us your design tips!
                      </h5>
                      <p>
                        Don’t be afraid to be wrong because you can’t learn
                        anything from a compliment.
                      </p>
                    </div>
                  </div>
                </div>
                <button
                  class="carousel-control-prev w-5 me-3"
                  type="button"
                  data-bs-target="#carouselExampleCaptions"
                  data-bs-slide="prev"
                >
                  <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button
                  class="carousel-control-next w-5 me-3"
                  type="button"
                  data-bs-target="#carouselExampleCaptions"
                  data-bs-slide="next"
                >
                  <span
                    class="carousel-control-next-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card">
              <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between">
                  <h6 class="mb-2">Sales by Country</h6>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-items-center">
                  <tbody>
                    <tr>
                      <td class="w-30">
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div>
                            <img
                              src="../../assets2/img/icons/flags/US.png"
                              alt="Country flag"
                            />
                          </div>
                          <div class="ms-4">
                            <p class="text-xs font-weight-bold mb-0">
                              Country:
                            </p>
                            <h6 class="text-sm mb-0">United States</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Sales:</p>
                          <h6 class="text-sm mb-0">2500</h6>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Value:</p>
                          <h6 class="text-sm mb-0">$230,900</h6>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <div class="col text-center">
                          <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                          <h6 class="text-sm mb-0">29.9%</h6>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="w-30">
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div>
                            <img
                              src="../../assets2/img/icons/flags/DE.png"
                              alt="Country flag"
                            />
                          </div>
                          <div class="ms-4">
                            <p class="text-xs font-weight-bold mb-0">
                              Country:
                            </p>
                            <h6 class="text-sm mb-0">Germany</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Sales:</p>
                          <h6 class="text-sm mb-0">3.900</h6>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Value:</p>
                          <h6 class="text-sm mb-0">$440,000</h6>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <div class="col text-center">
                          <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                          <h6 class="text-sm mb-0">40.22%</h6>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="w-30">
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div>
                            <img
                              src="../../assets2/img/icons/flags/GB.png"
                              alt="Country flag"
                            />
                          </div>
                          <div class="ms-4">
                            <p class="text-xs font-weight-bold mb-0">
                              Country:
                            </p>
                            <h6 class="text-sm mb-0">Great Britain</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Sales:</p>
                          <h6 class="text-sm mb-0">1.400</h6>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Value:</p>
                          <h6 class="text-sm mb-0">$190,700</h6>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <div class="col text-center">
                          <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                          <h6 class="text-sm mb-0">23.44%</h6>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="w-30">
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div>
                            <img
                              src="../../assets2/img/icons/flags/BR.png"
                              alt="Country flag"
                            />
                          </div>
                          <div class="ms-4">
                            <p class="text-xs font-weight-bold mb-0">
                              Country:
                            </p>
                            <h6 class="text-sm mb-0">Brasil</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Sales:</p>
                          <h6 class="text-sm mb-0">562</h6>
                        </div>
                      </td>
                      <td>
                        <div class="text-center">
                          <p class="text-xs font-weight-bold mb-0">Value:</p>
                          <h6 class="text-sm mb-0">$143,960</h6>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <div class="col text-center">
                          <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                          <h6 class="text-sm mb-0">32.14%</h6>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="card">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Categories</h6>
              </div>
              <div class="card-body p-3">
                <ul class="list-group">
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
                  >
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"
                      >
                        <i
                          class="ni ni-mobile-button text-white opacity-10"
                        ></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Devices</h6>
                        <span class="text-xs"
                          >250 in stock,
                          <span class="font-weight-bold">346+ sold</span></span
                        >
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      >
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
                  >
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"
                      >
                        <i class="ni ni-tag text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                        <span class="text-xs"
                          >123 closed,
                          <span class="font-weight-bold">15 open</span></span
                        >
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      >
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
                  >
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"
                      >
                        <i class="ni ni-box-2 text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                        <span class="text-xs"
                          >1 is active,
                          <span class="font-weight-bold">40 closed</span></span
                        >
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      >
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg"
                  >
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center"
                      >
                        <i class="ni ni-satisfied text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                        <span class="text-xs font-weight-bold">+ 430</span>
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      >
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                ©
                <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Pencatatan Prestasi</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About
                    Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted"
                    target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer> -->
      </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../../assets2/js/core/popper.min.js"></script>
    <script src="../../assets2/js/core/bootstrap.min.js"></script>
    <script src="../../assets2/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../assets2/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../../assets2/js/plugins/chartjs.min.js"></script>
    <script>
      var ctx1 = document.getElementById("chart-line").getContext("2d");

      var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

      gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
      gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
      gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");
      new Chart(ctx1, {
        type: "line",
        data: {
          labels: <?php echo json_encode($labels); ?>, // Mengambil data label dari PHP
          datasets: [
            {
              label: "Prestasi",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#5e72e4",
              backgroundColor: gradientStroke1,
              borderWidth: 3,
              fill: true,
              data: <?php echo json_encode($data); ?>, // Mengambil data jumlah prestasi dari PHP
              maxBarThickness: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: "index",
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
              },
              ticks: {
                display: true,
                padding: 10,
                color: "#fbfbfb",
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5],
              },
              ticks: {
                display: true,
                color: "#ccc",
                padding: 20,
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: "normal",
                  lineHeight: 2,
                },
              },
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
      var win = navigator.platform.indexOf("Win") > -1;
      if (win && document.querySelector("#sidenav-scrollbar")) {
        var options = {
          damping: "0.5",
        };
        Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
      }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
  </body>
</html>
