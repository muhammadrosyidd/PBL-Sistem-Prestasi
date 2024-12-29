<?php
require_once __DIR__ . '/../../config/Connection.php';

// Konfigurasi database
$db = new Connection("localhost", "", "", "PRESTASI");
$conn = $db->connect();

if ($conn === false) {
  die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

// Ambil data mahasiswa
$sqlMahasiswa = "SELECT nim, nama_depan, nama_belakang FROM mahasiswa";
$stmtMahasiswa = sqlsrv_query($conn, $sqlMahasiswa);
if ($stmtMahasiswa === false) {
  die("Query failed: " . print_r(sqlsrv_errors(), true));
}

// Ambil data dosen
$sqlDosen = "SELECT dosen_id, nama FROM dosen";
$stmtDosen = sqlsrv_query($conn, $sqlDosen);
if ($stmtDosen === false) {
  die("Query failed: " . print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil input dari form
  $judul = $_POST['judul'];
  $tempat = $_POST['tempat'];
  $link_kompetisi = $_POST['link_kompetisi'];
  $jumlah_peserta = $_POST['jumlah_peserta'];
  $tanggal_mulai = $_POST['tanggal_mulai'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
  $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
  $peringkat_id = $_POST['peringkat_id'];
  $mahasiswa_nims = $_POST['mahasiswa']; // Array of selected mahasiswa NIMs
  $peran_mahasiswa_ids = $_POST['peran_mahasiswa_id']; // Array of roles for each mahasiswa
  $pembimbing_ids = isset($_POST['pembimbing']) ? $_POST['pembimbing'] : []; // Array of selected pembimbing (supervisors)
  $peran_dosen_ids = isset($_POST['peran_dosen_id']) ? $_POST['peran_dosen_id'] : []; // Array of roles for each pembimbing

  // Capture the NIM from the input field
  $nim_input = $_POST['nim']; // NIM from the input field
  $peran_mahasiswa_input = 1; // Set peran_mahasiswa_id to 1

  // Handle file uploads
  $uploadDir = 'dokumen/'; // Set your upload directory
  $sertifikatPath = $uploadDir . basename($_FILES['sertifikat']['name']);
  $fotoKegiatanPath = $uploadDir . basename($_FILES['foto_kegiatan']['name']);
  $suratTugasPath = $uploadDir . basename($_FILES['surat_tugas']['name']);

  // Move uploaded files to the designated folder
  move_uploaded_file($_FILES['sertifikat']['tmp_name'], $sertifikatPath);
  move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], $fotoKegiatanPath);
  move_uploaded_file($_FILES['surat_tugas']['tmp_name'], $suratTugasPath);

  // Proses kategori
  $sqlKategori = "SELECT kategori_id FROM kategori WHERE nama_kategori = ?";
  $paramsKategori = [$judul];
  $stmtKategori = sqlsrv_query($conn, $sqlKategori, $paramsKategori);

  if ($stmtKategori === false) {
    die("Query kategori failed: " . print_r(sqlsrv_errors(), true));
  }

  $rowKategori = sqlsrv_fetch_array($stmtKategori, SQLSRV_FETCH_ASSOC);

  if ($rowKategori) {
    $kategori_id = $rowKategori['kategori_id'];
  } else {
    // Tambah kategori baru
    $sqlInsertKategori = "INSERT INTO kategori (nama_kategori) VALUES (?)";
    $stmtInsertKategori = sqlsrv_query($conn, $sqlInsertKategori, $paramsKategori);

    if ($stmtInsertKategori === false) {
      die("Insert kategori failed: " . print_r(sqlsrv_errors(), true));
    }

    // Ambil kategori_id yang baru saja ditambahkan
    $kategori_id_query = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS kategori_id");
    $rowKategoriId = sqlsrv_fetch_array($kategori_id_query, SQLSRV_FETCH_ASSOC);
    $kategori_id = $rowKategoriId['kategori_id'];
  }

  // Ambil nilai MAX(idpres) dari tabel prestasi
  $sqlMaxIdpres = "SELECT ISNULL(MAX(idpres), 0) AS max_idpres FROM prestasi";
  $stmtMaxIdpres = sqlsrv_query($conn, $sqlMaxIdpres);

  if ($stmtMaxIdpres === false) {
    die("Query MAX idpres gagal: " . print_r(sqlsrv_errors(), true));
  }

  $rowMaxIdpres = sqlsrv_fetch_array($stmtMaxIdpres, SQLSRV_FETCH_ASSOC);
  $idpres = $rowMaxIdpres['max_idpres'] + 1; // Jika NULL, mulai dari 1

  // Insert ke tabel prestasi dengan idpres manual
  $sqlPrestasi = "INSERT INTO prestasi (idpres, judul, tempat, link_kompetisi, tanggal_mulai, tanggal_akhir, jumlah_peserta, kategori_id, tingkat_lomba_id, peringkat_id, verifikasi_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Belum Terverifikasi')";
  $paramsPrestasi = [$idpres, $judul, $tempat, $link_kompetisi, $tanggal_mulai, $tanggal_akhir, $jumlah_peserta, $kategori_id, $tingkat_lomba_id, $peringkat_id];
  $stmtPrestasi = sqlsrv_query($conn, $sqlPrestasi, $paramsPrestasi);

  if ($stmtPrestasi === false) {
    die("Insert prestasi gagal: " . print_r(sqlsrv_errors(), true));
  } else {
    // Insert the NIM from the input field into the presma table
    $sqlPresmaInput = "INSERT INTO presma (nim, peran_mahasiswa_id, idpres) VALUES (?, ?, ?)";
    $paramsPresmaInput = [$nim_input, $peran_mahasiswa_input, $idpres];
    $stmtPresmaInput = sqlsrv_query($conn, $sqlPresmaInput, $paramsPresmaInput);

    if ($stmtPresmaInput === false) {
      die("Insert presma (input) gagal: " . print_r(sqlsrv_errors(), true));
    }

    // Insert ke tabel dokumen
    $sqlDokumen = "INSERT INTO dokumen (sertifikat, foto_kegiatan, surat_tugas, nomor_surat_tugas, tanggal_surat_tugas) VALUES (?, ?, ?, ?, ?)";
    $paramsDokumen = [$sertifikatPath, $fotoKegiatanPath, $suratTugasPath, $_POST['nomor_surat_tugas'], $_POST['tanggal_surat_tugas']];
    $stmtDokumen = sqlsrv_query($conn, $sqlDokumen, $paramsDokumen);

    if ($stmtDokumen === false) {
      die("Insert dokumen gagal: " . print_r(sqlsrv_errors(), true));
    }

    // Insert ke tabel presma for each selected mahasiswa
    foreach ($mahasiswa_nims as $index => $nim) {
      $sqlPresma = "INSERT INTO presma (nim, peran_mahasiswa_id, idpres) VALUES (?, ?, ?)";
      $paramsPresma = [$nim, $peran_mahasiswa_input, $idpres]; // Set peran_mahasiswa_id to 1
      $stmtPresma = sqlsrv_query($conn, $sqlPresma, $paramsPresma);

      if ($stmtPresma === false) {
        die("Insert presma gagal: " . print_r(sqlsrv_errors(), true));
      }
    }

    // Insert ke tabel dospem for each selected pembimbing
    if (!empty($pembimbing_ids)) { // Check if there are any pembimbing_ids
      foreach ($pembimbing_ids as $index => $dosen_id) {
        $peran_dosen_id = $peran_dosen_ids[$index]; // Get the corresponding role for the supervisor
        $sqlDospem = "INSERT INTO dospem (dosen_id, idpres, peran_dosen_id) VALUES (?, ?, ?)";
        $paramsDospem = [$dosen_id, $idpres, $peran_dosen_id];
        $stmtDospem = sqlsrv_query($conn, $sqlDospem, $paramsDospem);

        if ($stmtDospem === false) {
          die("Insert dospem gagal: " . print_r(sqlsrv_errors(), true));
        }
      }
    }
  }
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
    Input Prestasi
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets2/css/tambahmhs.css">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-gradient-warning position-absolute w-100">
    <span class="mask bg-gradient-warning opacity-5"></span>
  </div>
  <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
  <div class="main-content position-relative max-height-vh-100 h-100">
    <div class="card shadow-lg mx-4 card-profile-bottom"></div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0">Input Prestasi</p>
              </div>
            </div>
            <div class="card-body">
              <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nim" class="form-control-label">NIM Mahasiswa</label>
                      <input class="form-control" type="text" name="nim" id="nim" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="peran_mahasiswa_id" class="form-control-label">Peran</label>
                      <input type="hidden" class="form-control" value="1" name="peran_mahasiswa_id" id="peran_mahasiswa_id">
                      <div class="form-control" readonly>Ketua</div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="judul" class="form-control-label">Judul Kompetisi</label>
                      <input class="form-control" type="text" name="judul" id="judul" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tempat" class="form-control-label">Tempat Kompetisi</label>
                      <input class="form-control" type="text" name="tempat" id="tempat" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tingkat_lomba_id">Tingkat Kompetisi</label>
                      <select class="form-control" name="tingkat_lomba_id" required>
                        <option value="1">Regional</option>
                        <option value="2">Nasional</option>
                        <option value="3">Internasional</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="link_kompetisi" class="form-control-label">Link Kompetisi</label>
                      <input class="form-control" type="text" name="link_kompetisi" id="link_kompetisi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="jumlah_peserta" class="form-control-label">Jumlah Peserta</label>
                      <input class="form-control" type="number" name="jumlah_peserta" id="jumlah_peserta" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="peringkat_id">Peringkat</label>
                      <select class="form-control" name="peringkat_id" required>
                        <option value="1">Juara 1</option>
                        <option value="2">Juara 2</option>
                        <option value="3">Juara 3</option>
                        <option value="4">Harapan 1</option>
                        <option value="5">Harapan 2</option>
                        <option value="6">Harapan 3</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_mulai" class="form-control-label">Tanggal Mulai</label>
                      <input class="form-control" type="date" name="tanggal_mulai" id="tanggal_mulai" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_akhir" class="form-control-label">Tanggal Akhir</label>
                      <input class="form-control" type="date" name="tanggal_akhir" id="tanggal_akhir" required>
                    </div>
                  </div>
                </div>
                <hr class="horizontal dark">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nomor_surat_tugas" class="form-control-label">No Surat Tugas</label>
                      <input class="form-control" type="text" name="nomor_surat_tugas" id="nomor_surat_tugas" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_surat_tugas" class="form-control-label">Tanggal Surat Tugas</label>
                      <input class="form-control" type="date" name="tanggal_surat_tugas" id="tanggal_surat_tugas" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="surat_tugas" class="form-label">File Surat Tugas</label>
                    <input class="form-control" type="file" name="surat_tugas" id="surat_tugas" required>
                  </div>
                  <div class="mb-3">
                    <label for="sertifikat" class="form-label">File Sertifikat</label>
                    <input class="form-control" type="file" name="sertifikat" id="sertifikat" required>
                  </div>
                  <div class="mb-3">
                    <label for="foto_kegiatan" class="form-label">Foto Kegiatan</label>
                    <input class="form-control" type="file" name="foto_kegiatan" id="foto_kegiatan" required>
                  </div>
                  <div class="container mt-4">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Data Mahasiswa</h5>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="mahasiswaTable" class="table table-striped">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th>Mahasiswa</th>
                                <th>Peran</th>
                                <th class="text-center">Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr class="text-center">
                                <td class="text-center">1</td>
                                <td class="text-center">
                                  <select name="mahasiswa[]" class="form-control" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    <?php while ($row = sqlsrv_fetch_array($stmtMahasiswa, SQLSRV_FETCH_ASSOC)) { ?>
                                      <option value="<?php echo $row['nim']; ?>">
                                        <?php echo $row['nim'] . ' - ' . $row['nama_depan'] . ' ' . $row['nama_belakang']; ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                </td>
                                </td>
                                <td class="text-center">
                                  <select name="peran_mahasiswa_id[]" class="form-control" required>
                                    <option value="2">Anggota</option>
                                    <option value="3">Personal</option>
                                  </select>
                                </td>
                                <td class="text-center">
                                  <button type="button" class="btn btn-danger mt-0 mb-0" onclick="deleteRow(this)">
                                    <i class="fas fa-times"></i>
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <button type="button" class="btn btn-primary" onclick="addRow()">
                            <i class="fas fa-plus"></i> Tambah Mahasiswa
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="container mt-4">
                    <div class="card">
                      <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Data Pembimbing</h5>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="pembimbingTable" class="table table-striped">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th>Pembimbing</th>
                                <th>Peran</th>
                                <th class="text-center">Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr class="text-center">
                                <td class="text-center">1</td>
                                <td class="text-center">
                                  <select name="pembimbing[]" class="form-control" required>
                                    <?php while ($row = sqlsrv_fetch_array($stmtDosen, SQLSRV_FETCH_ASSOC)) { ?>
                                      <option value="<?php echo $row['dosen_id']; ?>">
                                        <?php echo $row['nama']; ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                </td>
                                <td class="text-center">
                                  <select name="peran_dosen_id[]" class="form-control" required>
                                    <option>Pilih Peran</option>
                                    <option value="1">Melakukan pembinaan kegiatan mahasiswa di <br>
                                      bidang akademik (PA) dan kemahasiswaan <br>
                                      (BEM, Maperwa, dan lain-lain)</option>
                                    <option value="2">Membimbing mahasiswa menghasilkan
                                      produk saintifik bereputasi dan mendapat
                                      pengakuan tingkat Internasional</option>
                                    <option value="3">Membimbing mahasiswa menghasilkan
                                      produk saintifik bereputasi dan mendapat
                                      pengakuan tingkat Nasional</option>
                                    <option value="4">Membimbing mahasiswa mengikuti kompetisi
                                      dibidang akademik dan kemahasiswaan
                                      bereputasi dan mencapai juara tingkat
                                      Internasional</option>
                                    <option value="5">Membimbing mahasiswa mengikuti kompetisi
                                      dibidang akademik dan kemahasiswaan
                                      bereputasi dan mencapai juara tingkat
                                      Nasional</option>
                                  </select>
                                </td>
                                <td class="text-center">
                                  <button type="button" class="btn btn-danger mt-0 mb-0" onclick="deleteRow1(this)">
                                    <i class="fas fa-times"></i>
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <button type="button" class="btn btn-primary" onclick="addRow1()">
                            <i class="fas fa-plus"></i> Tambah Pembimbing
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-warning btn-sm">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function addRow() {
      var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);

      var cell1 = row.insertCell(0);
      var cell2 = row.insertCell(1);
      var cell3 = row.insertCell(2);
      var cell4 = row.insertCell(3);

      // Pusatkan nomor baris
      cell1.className = "text-center"; // Tambahkan kelas text-center untuk sel 1
      cell1.innerHTML = rowCount + 1; // Perbarui nomor baris

      // Pusatkan elemen select
      cell2.innerHTML = `
        <select name="mahasiswa[]" class="form-control">
            <option>Pilih Mahasiswa</option>
            <!-- Tambahkan opsi secara dinamis dari database -->
        </select>`;

      cell3.innerHTML = `
        <select name="peran[]" class="form-control">
            <option>Pilih Peran</option>
            <!-- Tambahkan opsi secara dinamis -->
        </select>`;

      // Pusatkan tombol hapus
      cell4.className = "text-center"; // Tambahkan kelas text-center untuk sel 4
      cell4.innerHTML = `
        <button type="button" class="btn btn-danger mt-0 mb-0" onclick="deleteRow(this)">
            <i class="fas fa-times"></i>
        </button>`;
    }

    function deleteRow(button) {
      var row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
      updateRowNumbers();
    }

    function updateRowNumbers() {
      var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
      for (var i = 0; i < table.rows.length; i++) {
        table.rows[i].cells[0].innerHTML = i + 1;
      }
    }
  </script>
  <script>
    function addRow1() {
      var table = document.getElementById("pembimbingTable").getElementsByTagName('tbody')[0];
      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);

      var cell1 = row.insertCell(0);
      var cell2 = row.insertCell(1);
      var cell3 = row.insertCell(2);
      var cell4 = row.insertCell(3);

      // Pusatkan nomor baris
      cell1.className = "text-center"; // Tambahkan kelas text-center untuk sel 1
      cell1.innerHTML = rowCount + 1; // Perbarui nomor baris

      // Pusatkan elemen select
      cell2.innerHTML = `
            <select name="pembimbing[]" class="form-control">
                <option>Pilih Pembimbing</option>
                <!-- Tambahkan opsi secara dinamis dari database -->
            </select>`;

      cell3.innerHTML = `
            <select name="pembimbing_peran[]" class="form-control">
                <option>Pilih Peran</option>
                <!-- Tambahkan opsi secara dinamis -->
            </select>`;

      // Pusatkan tombol hapus
      cell4.className = "text-center"; // Tambahkan kelas text-center untuk sel 4
      cell4.innerHTML = `
            <button type="button" class="btn btn-danger mt-0 mb-0" onclick="deleteRow1(this)">
                <i class="fas fa-times"></i>
            </button>`;
    }

    function deleteRow1(button) {
      var row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
      updateRowNumbers1();
    }

    function updateRowNumbers1() {
      var table = document.getElementById("pembimbingTable").getElementsByTagName('tbody')[0];
      for (var i = 0; i < table.rows.length; i++) {
        table.rows[i].cells[0].innerHTML = i + 1;
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