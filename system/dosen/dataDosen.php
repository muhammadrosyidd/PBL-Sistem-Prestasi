<?php

// Include file koneksi
require_once __DIR__ . '/../../config/Connection.php';

// Mengambil data dosen untuk SQL Server
$query = "SELECT * FROM dosen ORDER BY nama ASC";
$result = sqlsrv_query($conn, $query);

// Menangani kesalahan jika query gagal
if (!$result) {
    die("Query gagal: " . print_r(sqlsrv_errors(), true));
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
    Data Dosen
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
      data-scroll="false">

    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-41">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Dosen</h6>
              <a href="tambahDosen.php"><button type="button" class="btn bg-gradient-warning   mt-2 mb-0">+
                  Dosen</button></a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIDN
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Dosen
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telepon
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $no = 1; // Inisialisasi nomor urut
                      while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                          $dosen_id = $row['dosen_id']; // Mengambil dosen_id dari query

                          echo "<tr>
                                      <td class='text-center text-xxs font-weight-bold mb-0'>{$no}</td>
                                      <td class='text-center text-xs font-weight-bold mb-0'>{$row['nidn']}</td>
                                      <td class='text-center text-xs font-weight-bold mb-0'>{$row['nama']}</td> 
                                      <td class='text-center text-xs font-weight-bold mb-0'>{$row['telepon']}</td>
                                      <td class='align-middle text-center text-sm'>
                                          <a href='editDosen.php?dosen_id=" . urlencode($dosen_id) . "'>
                                              <span class='btn btn-sm bg-gradient-primary'>Edit</span>
                                          </a>
                                          <form action='hapusDosen.php' method='POST' style='display:inline;' onsubmit='return confirmDelete();'>
                                              <input type='hidden' name='dosen_id' value='" . htmlspecialchars($dosen_id) . "'>
                                              <button type='submit' class='btn btn-sm bg-gradient-danger'>Hapus</button>
                                          </form>
                                      </td>
                                </tr>";
                          $no++; // Increment nomor urut
                      }
                      ?>
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
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>