<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
  <title>
    Data Prestasi
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

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
  <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">

    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-41">
      <div class="row">
        
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Prestasi</h6>
              <a href="inputPrestasi.html"><button class="btn bg-gradient-warning">+ Prestasi</button></a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kompetisi</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-6">Aksi</th>
                      
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                          <span style="margin: 1rem;" class="text-center text-xs font-weight-bold">KMIPN</span>
                      </td>
                      <td>
                        <span class="text-center text-xs font-weight-bold">24 Oktober 2024</span>
                      </td>
                      <td>
                        <span class="text-center text-xs font-weight-bold">Saintek</span>
                      </td>
                      <td>
                          <span class="me-2 text-xs font-weight-bold" style="color: red;">Belum Diverifikasi</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="action cursor-pointer text-center text-xs font-weight-bold badge badge-sm bg-gradient-primary" onclick="toggleDetails()">Detail</span>
                      </td>
                    </tr>
                    <tr style="margin-left: 2rem;" id="details" class="details">
                      <td class="text-xs" colspan="2">
                        <strong>Detail Prestasi:</strong><br>
                        <b>Tingkat:</b> Nasional<br>
                        <b>Link Kompetisi:</b> https%3A%2F%2Fwww<br>
                        <b>Tanggal Mulai:</b> 24 Okt 2024<br>
                        <b>Tanggal Akhir:</b> 27 okt 2024<br>
                        <b>Tempat:</b> Politeknik Negeri Jember<br>
                        <b>Jumlah Peserta:</b> 5373<br>
                        <b>Surat Tugas:</b><br>
                        No: 372/PDG4/8KP/2024 <br>
                        Tanggal: 23 Okt 2024 <br>
                        <b>Lampiran:</b><br><br>
                        <button class="btn bg-gradient-warning">Verifikasi</button>
                      </td>
                      <td class="text-xs" colspan="3">
                        <b>Peserta:</b><br>
                        2341760193 - Keysha Arindra Fabian<br>
                        2341760121 - Muhammad Rosyid<br>
                        2341760070 - Imel Theresia Br Sembiring<br>
                        2341760070 - Farel Maryam Laili Hajiri<br>
                        2341760070 - Satrio Dian Nugroho<br>
                        <b>Pembimbing:</b><br>
                        Endah Septa Sintiya. SPd., MKom
                        
                      </td>
                      
                    </tr>
                  </tbody>
                </table>
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
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script>
    function toggleDetails() {
        var details = document.getElementById("details");
        if (details.style.display === "none") {
            details.style.display = "table-row";
        } else {
            details.style.display = "none";
        }
    }
</script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>