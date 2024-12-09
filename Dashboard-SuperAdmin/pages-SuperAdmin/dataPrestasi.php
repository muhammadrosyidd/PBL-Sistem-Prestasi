<?php
$use_driver = 'sqlsrv'; // mysql atau sqlsrv 
$host = "DAYDREAMER"; //'localhost'; 
$username = ''; //'sa'; 
$password = ''; 
$database = 'PencatatanPrestasi'; 
$db; 

if ($use_driver == 'sqlsrv') { 
    $credential = [ 
        'Database' => $database, 
        'UID' => $username, 
        'PWD' => $password 
    ]; 
    
    try { 
        $db = sqlsrv_connect($host, $credential); 
        
        if (!$db) { 
            die("Connection failed: " . sqlsrv_errors()[0]['message']); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
}

// Fetch data from the prestasi table
$sql = "SELECT * FROM prestasi";
$stmt = sqlsrv_query($db, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/jti.png">
  <title>Data Prestasi</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <style>
    ::-webkit-scrollbar {
            display: none;
        }
  </style>
  <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html" target="_blank">
        <img src="../assets/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dashboard.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataPengguna.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Pengguna</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataDosen.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Dosen</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataMahasiswa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Mahasiswa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../pages-SuperAdmin/dataPrestasi.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Prestasi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="informasiLomba.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Informasi Lomba</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="laporan.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
    </nav>
    <div class="container-fluid py-41">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Prestasi</h6>
              <a href="inputPrestasi.php"><button class="btn bg-gradient-warning">+ Prestasi</button></a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kompetisi</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tingkat</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td class="text-center text-xxs font-weight-bold mb-0"><?php echo $no; ?></td>
                        <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['judul_kompetisi'] ?? ''); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['tanggal_mulai'] ? $row['tanggal_mulai']->format('d-m-Y') : ''); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['tingkat_kompetisi'] ?? ''); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['status_prestasi'] ?? ''); ?></span>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <button class="btn bg-gradient-primary mt-0 mb-0" onclick="toggleDetails(<?php echo $row['id']; ?>)">Detail</button>
                        </td>
                    </tr>
                    <tr id="details-<?php echo $row['id']; ?>" class="details" style="display: none;">
                        <td colspan="6" style="padding: 1rem; font-size : 12px;">
                            <strong>Detail Prestasi:</strong><br>
                            <b>Tingkat:</b> <?php echo htmlspecialchars($row['tingkat_kompetisi'] ?? ''); ?><br>
                            <b>Link Kompetisi:</b> <a href="<?php echo htmlspecialchars($row['link_kompetisi'] ?? ''); ?>"><?php echo htmlspecialchars($row['link_kompetisi'] ?? ''); ?></a><br>
                            <b>Tanggal Mulai:</b> <?php echo htmlspecialchars($row['tanggal_mulai'] ? $row['tanggal_mulai']->format('d-m-Y') : ''); ?><br>
                            <b>Tanggal Akhir:</b> <?php echo htmlspecialchars($row['tanggal_akhir'] ? $row['tanggal_akhir']->format('d-m-Y') : ''); ?><br>
                            <b>Tempat:</b> <?php echo htmlspecialchars($row['tempat_kompetisi'] ?? ''); ?><br>
                            <b>Jumlah Peserta:</b> <?php echo htmlspecialchars($row['jumlah_peserta'] ?? ''); ?><br>
                            <b>Peringkat Juara:</b> <?php echo htmlspecialchars($row['peringkat_juara'] ?? ''); ?><br>
                            <b>Surat Tugas:</b><br>
                            <span>No: <?php echo htmlspecialchars($row['no_surat_tugas'] ?? ''); ?></span><br>
                            <span>Tanggal: <?php echo htmlspecialchars($row['tanggal_surat_tugas'] ? $row['tanggal_surat_tugas']->format('d-m-Y') : ''); ?></span><br>
                            <b>Pembimbing:</b><br>
                            <?php echo htmlspecialchars($row['nama_dosen'] ?? ''); ?><br>
                            <button class="btn bg-gradient-warning">Verifikasi</button>
                        </td>
                    </tr>
                    <?php
                    $no++; ?>
                    <?php endwhile; ?>
                </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
  function toggleDetails(id) {
      var detailsRow = document.getElementById("details-" + id);
      if (detailsRow.style.display === "none") {
          detailsRow.style.display = "table-row"; // Show the details row
      } else {
          detailsRow.style.display = "none"; // Hide the details row
      }
  }
  </script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>