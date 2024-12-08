<?php
require_once __DIR__ . '/../../config/Connection.php';

// Query untuk mengambil data dari tabel user, admin, dan superadmin
$query = "
    SELECT 
    u.username, 
    u.password, 
    u.role,
    ISNULL(a.nama, sa.nama) AS nama_admin,
    ISNULL(a.jabatan, sa.jabatan) AS jabatan,
    ISNULL(a.jeniskelamin, sa.jeniskelamin) AS jenis_kelamin,
    ISNULL(a.telepon, sa.telepon) AS no_telepon,
    ISNULL(a.alamat, sa.alamat) AS alamat
FROM [user] u
LEFT JOIN [admin] a ON u.username = a.username
LEFT JOIN [superadmin] sa ON u.username = sa.username
WHERE u.role IN (1, 2)
ORDER BY u.role ASC;";

$result = sqlsrv_query($conn, $query);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
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
    Data Pengguna
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
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html "
        target="_blank">
        <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../pages-SuperAdmin/dashboard.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../pages-SuperAdmin/dataPengguna.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Pengguna</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataDosen.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Dosen</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataMahasiswa.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Mahasiswa</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link " href="../pages-SuperAdmin/dataPrestasi.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Data Prestasi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="informasiLomba.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Informasi Lomba</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="laporan.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
          </a>
        </li>

      </ul>
    </div>

  </aside>
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
            <h6>Data Pengguna</h6>
        <a href="../pengguna/tambahPengguna.php">
            <button type="button" class="btn bg-gradient-warning mt-2 mb-0">+ Pengguna</button>
        </a>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jabatan</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Kelamin</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Username</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Password</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Telepon</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1; // Inisialisasi nomor urut
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    // Validasi dan masking data
                    $nama_admin = isset($row['nama_admin']) ? htmlspecialchars($row['nama_admin']) : '-';
                    $jabatan = isset($row['jabatan']) ? htmlspecialchars($row['jabatan']) : '-';
                    $jenis_kelamin = isset($row['jenis_kelamin']) ? htmlspecialchars($row['jenis_kelamin']) : '-';
                    $username = isset($row['username']) ? htmlspecialchars($row['username']) : '-';
                    $masked_password = isset($row['password']) ? str_repeat('*', strlen($row['password'])) : '-';
                    $no_telepon = isset($row['no_telepon']) ? htmlspecialchars($row['no_telepon']) : '-';
                    $alamat = isset($row['alamat']) ? htmlspecialchars($row['alamat']) : '-';
                    $role = isset($row['role']) ? htmlspecialchars($row['role']) : '-';
                    // Output data sesuai struktur tabel
                    echo "
                    <tr>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$no}</td>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$nama_admin}</td>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$jabatan}</td>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$jenis_kelamin}</td>
                        <td class='text-center text-xs font-weight-bold mb-0'>{$username}</td>
                        <td class='text-center text-xs font-weight-bold mb-0'>{$masked_password}</td>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$no_telepon}</td>
                        <td class='text-center text-xxs font-weight-bold mb-0'>{$alamat}</td>
                        <td class='text-center text-xs font-weight-bold mb-0'>{$role}</td>
                        <td class='align-middle text-center text-sm'>
                            <a href='editPengguna.php?username=" . urlencode($username) . "'>
                                <span class='btn btn-sm bg-gradient-primary'>Edit</span>
                            </a>
                            <form action='hapusPengguna.php' method='POST' style='display:inline;' onsubmit='return confirmDelete();'>
                                <input type='hidden' name='username' value='" . htmlspecialchars($username) . "'>
                                <button type='submit' class='btn btn-sm bg-gradient-danger'>Hapus</button>
                            </form>
                        </td>
                    </tr>";
                    $no++;
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
  <script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus pengguna ini?");
    }
</script>
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>