<?php
require_once __DIR__ . '/../../config/Connection.php';

// Start session untuk mendapatkan username
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  header("Location: /PBL-Sistem-Prestasi/system/pages-Sign-in/Login.php");
  exit();
}

// Ambil username dari session
$username = $_SESSION['username'];

// Buat koneksi ke database
$db = new Connection("localhost", "", "", "PRESTASI");
$conn = $db->connect();

if ($conn === false) {
  die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}

// Query untuk mengambil data prestasi jika username sama dengan NIM di tabel presma
$sql = "
    SELECT 
        p.judul, 
        p.tempat, 
        p.link_kompetisi, 
        FORMAT(p.tanggal_mulai, 'dd MMM yyyy') AS tanggal_mulai, 
        FORMAT(p.tanggal_akhir, 'dd MMM yyyy') AS tanggal_akhir, 
        p.jumlah_peserta, 
        k.nama_kategori, 
        pr.peran_mahasiswa_id
    FROM prestasi p
    JOIN presma pr ON p.idpres = pr.idpres
    JOIN kategori k ON p.kategori_id = k.kategori_id
    WHERE pr.nim = ?
";

$params = [$username];
$stmt = sqlsrv_query($conn, $sql, $params);

// Periksa apakah query berhasil
if ($stmt === false) {
  die("Query failed: " . print_r(sqlsrv_errors(), true));
}

// Menyimpan hasil query ke array
$prestasi = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  // Karena tanggal sudah diformat di query SQL, tidak perlu mengonversinya lagi di PHP
  $prestasi[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Debug: Periksa apakah data prestasi ditemukan
if (empty($prestasi)) {
  echo "Tidak ada data prestasi ditemukan untuk username: $username";
  exit();
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
              <a href="../prestasi/inputPrestasi.php"><button class="btn bg-gradient-warning">+ Prestasi</button></a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kompetisi</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Surat Tugas</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($prestasi)): ?>
                      <?php
                      $no = 1; // Deklarasikan variabel $no
                      foreach ($prestasi as $index => $data):
                      ?>
                        <tr>
                          <td class="text-center text-xxs font-weight-bold mb-0"><?= $no++; ?></td>
                          <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($data['judul'] ?? ''); ?></span>
                          </td>
                          <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0">
                              <?= htmlspecialchars($data['tanggal_mulai'] ?? ''); ?>
                              -
                              <?= htmlspecialchars($data['tanggal_akhir'] ?? ''); ?>
                            </span>
                          </td>
                          <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($data['nama_kategori'] ?? ''); ?></span>
                          </td>
                          <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0">Belum Diverifikasi</span>
                          </td>
                          <td class="text-center">
                            <span class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($data['nomor_surat_tugas'] ?? '-'); ?></span>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <button class="btn bg-gradient-primary mt-0 mb-0" onclick="toggleDetails(<?= $index; ?>)">Detail</button>
                          </td>
                        </tr>
                        <!-- Baris Detail -->
                        <tr id="details-<?= $index; ?>" style="display:none;">
                          <td colspan="7" style="padding: 1rem; font-size: 12px;">
                            <strong>Detail Prestasi:</strong><br>
                            <b>Tingkat:</b> <?= htmlspecialchars($data['peran_mahasiswa_id'] ?? ''); ?><br>
                            <b>Link Kompetisi:</b>
                            <a href="<?= htmlspecialchars($data['link_kompetisi'] ?? '#'); ?>" target="_blank">
                              <?= htmlspecialchars($data['link_kompetisi'] ?? ''); ?>
                            </a><br>
                            <b>Tanggal Mulai:</b> <?= htmlspecialchars($data['tanggal_mulai'] ?? '-'); ?><br>
                            <b>Tanggal Akhir:</b> <?= htmlspecialchars($data['tanggal_akhir'] ?? '-'); ?><br>
                            <b>Tempat:</b> <?= htmlspecialchars($data['tempat'] ?? '-'); ?><br>
                            <b>Jumlah Peserta:</b> <?= htmlspecialchars($data['jumlah_peserta'] ?? '-'); ?><br>
                            <b>Peringkat Juara:</b> <?= htmlspecialchars($data['nama_peringkat'] ?? '-'); ?><br>
                            <b>Surat Tugas:</b> <br>
                            No: <?= htmlspecialchars($data['nomor_surat_tugas'] ?? '-'); ?><br>
                            Tanggal: <?= htmlspecialchars($data['tanggal_surat_tugas'] ?? '-'); ?><br>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">Tidak ada data prestasi ditemukan.</td>
                      </tr>
                    <?php endif; ?>
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
    function toggleDetails(index) {
      // Mendapatkan elemen baris detail berdasarkan ID
      const detailsRow = document.getElementById(`details-${index}`);
      if (detailsRow) {
        // Toggle visibilitas baris detail
        detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
      }
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>