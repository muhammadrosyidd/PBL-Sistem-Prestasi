<?php
require_once __DIR__ . '/../../config/Connection.php';
// Fetch data from the prestasi table
$sql = "SELECT p.*, tl.nama_tingkat, pr.nama_peringkat, d.surat_tugas, d.tanggal_surat_tugas, d.nomor_surat_tugas 
        FROM prestasi p 
        JOIN tingkatLomba tl ON p.tingkat_lomba_id = tl.tingkat_lomba_id
        JOIN peringkat pr ON p.peringkat_id = pr.peringkat_id
        JOIN dokumen d ON p.dokumen_id = d.dokumen_id";

// Get the database connection resource
$conn = $db->getConnection();
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("Query failed: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
  <title>Data Prestasi</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
  <style>
    ::-webkit-scrollbar {
      display: none;
    }
  </style>
  <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
  <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
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
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor Surat Tugas</th> <!-- Kolom Baru -->
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1; // Inisialisasi nomor urut
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td class="text-center text-xxs font-weight-bold mb-0"><?php echo $no++; ?></td>
                            <td class="text-center">
                                <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['judul'] ?? ''); ?></span>
                            </td>
                            <td class="text-center">
                                <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['tanggal_mulai'] ? $row['tanggal_mulai']->format('d-m-Y') : ''); ?></span>
                            </td>
                            <td class="text-center">
                                <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['nama_tingkat'] ?? ''); ?></span>
                            </td>
                            <td class="text-center">
                                <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['verifikasi_status'] ?? ''); ?></span>
                            </td>
                            <td class="text-center"> <!-- Sel Baru untuk Nomor Surat Tugas -->
                                <span class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['nomor_surat_tugas'] ?? ''); ?></span>
                            </td>
                            <td class="align-middle text-center text-sm">
                                <button class="btn bg-gradient-primary mt-0 mb-0" onclick="toggleDetails(<?php echo $row['prestasi_id']; ?>)">Detail</button>
                            </td>
                        </tr>
                        <!-- Baris Detail -->
                        <tr id="details-<?php echo $row['prestasi_id']; ?>" style="display:none;">
                          <td colspan="7" style="padding: 1rem; font-size: 12px;">
                              <strong>Detail Prestasi:</strong><br>
                              <b>Tingkat:</b> <?php echo htmlspecialchars($row['nama_tingkat'] ?? ''); ?><br>
                              <b>Link Kompetisi:</b> <a href="<?php echo htmlspecialchars($row['link_kompetisi'] ?? ''); ?>"><?php echo htmlspecialchars($row['link_kompetisi'] ?? ''); ?></a><br>
                              <b>Tanggal Mulai:</b> <?php echo htmlspecialchars($row['tanggal_mulai'] ? $row['tanggal_mulai']->format('d-m-Y') : ''); ?><br>
                              <b>Tanggal Akhir:</b> <?php echo htmlspecialchars($row['tanggal_akhir'] ? $row['tanggal_akhir']->format('d-m-Y') : ''); ?><br>
                              <b>Tempat:</b> <?php echo htmlspecialchars($row['tempat'] ?? ''); ?><br>
                              <b>Jumlah Peserta:</b> <?php echo htmlspecialchars($row['jumlah_peserta'] ?? ''); ?><br>
                              <b>Peringkat Juara:</b> <?php echo htmlspecialchars($row['nama_peringkat'] ?? ''); ?><br>
                              <b>Surat Tugas:</b><br> No: <?php echo htmlspecialchars($row['nomor_surat_tugas'] ?? ''); ?><br> Tanggal: <?php echo htmlspecialchars($row['tanggal_surat_tugas'] ? $row['tanggal_surat_tugas']->format('d-m-Y') : ''); ?><br><br>

                              <?php if ($row['verifikasi_status'] !== 'Sudah Terverifikasi'): ?>
                                  <form method="POST" action="verifikasi.php">
                                      <input type="hidden" name="prestasi_id" value="<?php echo $row['prestasi_id']; ?>">
                                      <button type="submit" name="verifikasi" class="btn bg-gradient-warning">Verifikasi</button>
                                  </form>
                              <?php else: ?>
                                  <span class="text-xs font-weight-bold text-success">Sudah Terverifikasi</span>
                              <?php endif; ?>
                          </td>
                      </tr>
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
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../../assets2/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>