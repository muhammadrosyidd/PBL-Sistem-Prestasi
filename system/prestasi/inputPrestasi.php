<?php
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Data untuk tabel prestasi
  $judul = $_POST['judul'];
  $tempat = $_POST['tempat'];
  $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
  $link_kompetisi = $_POST['link_kompetisi'];
  $jumlah_peserta = $_POST['jumlah_peserta'];
  $peringkat_id = $_POST['peringkat_id'];
  $tanggal_mulai = $_POST['tanggal_mulai'];
  $tanggal_akhir = $_POST['tanggal_akhir'];

  // Data untuk mahasiswa dan dosen
  $mahasiswa = isset($_POST['mahasiswa']) ? $_POST['mahasiswa'] : [];
  $peranMahasiswa = isset($_POST['peran_mahasiswa_id']) ? $_POST['peran_mahasiswa_id'] : [];
  $pembimbing = isset($_POST['dosen_id']) ? $_POST['dosen_id'] : [];
  $peranPembimbing = isset($_POST['peran_dosen_id']) ? $_POST['peran_dosen_id'] : [];

  // Mengatur file upload
  $uploadDir = 'uploads/';
  $fileSuratTugas = null;
  $fileSertifikat = null;
  $fotoKegiatan = null;
  $proposal = null;

  // Mengupload file jika ada
  if (isset($_FILES['surat_tugas']) && $_FILES['surat_tugas']['error'] == 0) {
    $fileSuratTugas = $uploadDir . basename($_FILES['surat_tugas']['name']);
    move_uploaded_file($_FILES['surat_tugas']['tmp_name'], $fileSuratTugas);
  }

  if (isset($_FILES['sertifikat']) && $_FILES['sertifikat']['error'] == 0) {
    $fileSertifikat = $uploadDir . basename($_FILES['sertifikat']['name']);
    move_uploaded_file($_FILES['sertifikat']['tmp_name'], $fileSertifikat);
  }

  if (isset($_FILES['foto_kegiatan']) && $_FILES['foto_kegiatan']['error'] == 0) {
    $fotoKegiatan = $uploadDir . basename($_FILES['foto_kegiatan']['name']);
    move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], $fotoKegiatan);
  }

  if (isset($_FILES['proposal']) && $_FILES['proposal']['error'] == 0) {
    $proposal = $uploadDir . basename($_FILES['proposal']['name']);
    move_uploaded_file($_FILES['proposal']['tmp_name'], $proposal);
  }

  // Pastikan koneksi database valid
  $conn = (new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI"))->connect();

  if ($conn) {
    // Query untuk menyimpan data prestasi
    $queryPrestasi = "INSERT INTO prestasi (judul, tempat_kompetisi, tingkat_kompetisi, link_kompetisi, jumlah_peserta, peringkat_juara, tanggal_mulai, tanggal_akhir) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtPrestasi = sqlsrv_prepare($conn, $queryPrestasi, [
      &$judul,
      &$tempat,
      &$tingkat_lomba_id,
      &$link_kompetisi,
      &$jumlah_peserta,
      &$peringkat_id,
      &$tanggal_mulai,
      &$tanggal_akhir
    ]);

    if (sqlsrv_execute($stmtPrestasi)) {
      $prestasiId = sqlsrv_insert_id($conn);

      // Simpan data mahasiswa ke tabel presma
      if (!empty($mahasiswa)) {
        foreach ($mahasiswa as $index => $mhs) {
          $queryPresma = "INSERT INTO presma (nim, prestasi_id, peran_mahasiswa_id) VALUES (?, ?, ?)";
          $stmtPresma = sqlsrv_prepare($conn, $queryPresma, [
            &$mhs,
            &$prestasiId,
            &$peranMahasiswa[$index]
          ]);
          sqlsrv_execute($stmtPresma);
        }
      }

      // Simpan data dosen ke tabel dospem
      if (!empty($pembimbing)) {
        foreach ($pembimbing as $index => $dosen) {
          $queryDospem = "INSERT INTO dospem (dosen_id, prestasi_id, peran_dosen_id) VALUES (?, ?, ?)";
          $stmtDospem = sqlsrv_prepare($conn, $queryDospem, [
            &$dosen,
            &$prestasiId,
            &$peranPembimbing[$index]
          ]);
          sqlsrv_execute($stmtDospem);
        }
      }

      // Simpan dokumen ke tabel dokumen
      $queryDokumen = "INSERT INTO dokumen (prestasi_id, surat_tugas, sertifikat, foto_kegiatan, proposal) VALUES (?, ?, ?, ?, ?)";
      $stmtDokumen = sqlsrv_prepare($conn, $queryDokumen, [
        &$prestasiId,
        &$fileSuratTugas,
        &$fileSertifikat,
        &$fotoKegiatan,
        &$proposal
      ]);

      if (sqlsrv_execute($stmtDokumen)) {
        echo "Data berhasil disimpan!";
      } else {
        echo "Error: Gagal menyimpan dokumen.";
      }
    } else {
      echo "Error: Gagal menyimpan data prestasi.";
    }
  } else {
    echo "Error: Koneksi database gagal.";
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
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html " target="_blank">
        <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100" alt="main_logo">
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
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label for="peran" class="form-control-label">Peran</label>
                      <input class="form-control" value="Ketua Tim" name="peran" id="peran" readonly>
                    </div>
                  </div> -->
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
                        <option value="Regional">Regional</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
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
                        <option value="Juara 1">Juara 1</option>
                        <option value="Juara 2">Juara 2</option>
                        <option value="Juara 3">Juara 3</option>
                        <option value="Harapan 1">Harapan 1</option>
                        <option value="Harapan 2">Harapan 2</option>
                        <option value="Harapan 3">Harapan 3</option>
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
                      <label for="noSuratTugas" class="form-control-label">No Surat Tugas</label>
                      <input class="form-control" type="text" name="noSuratTugas" id="noSuratTugas" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggalSuratTugas" class="form-control-label">Tanggal Surat Tugas</label>
                      <input class="form-control" type="date" name="tanggalSuratTugas" id="tanggalSuratTugas" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="fileSuratTugas" class="form-label">File Surat Tugas</label>
                    <input class="form-control" type="file" name="fileSuratTugas" id="fileSuratTugas" required>
                  </div>
                  <div class="mb-3">
                    <label for="fileSertifikat" class="form-label">File Sertifikat</label>
                    <input class="form-control" type="file" name="fileSertifikat" id="fileSertifikat" required>
                  </div>
                  <div class="mb-3">
                    <label for="fotoKegiatan" class="form-label">Foto Kegiatan</label>
                    <input class="form-control" type="file" name="fotoKegiatan" id="fotoKegiatan" required>
                  </div>
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
                                <input type="text" name="mahasiswa[]" class="form-control">
                                <!-- <select name="mahasiswa[]" class="form-control"> -->
                                  <!-- <option>Pilih Mahasiswa</option> -->
                                  <!-- Tambahkan opsi secara dinamis dari database -->
                                <!-- </select> -->
                              </td>
                              <td class="text-center">
                                <input type="text" name="peran[]" class="form-control">
                                <!-- <select name="peran[]" class="form-control"> -->
                                  <!-- <option>Pilih Peran</option> -->
                                  <!-- Tambahkan opsi secara dinamis -->
                                <!-- </select> -->
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
                                <input type="text" name="pembimbing[]" class="form-control">
                                <!-- <select name="pembimbing[]" class="form-control"> -->
                                  <!-- <option>Pilih Pembimbing</option> -->
                                  <!-- Tambahkan opsi secara dinamis -->
                                <!-- </select> -->
                              </td>
                              <td class="text-center">
                                <input type="text" name="pembimbing_peran[]" class="form-control">
                                <!-- <select name="pembimbing_peran[]" class="form-control"> -->
                                  <!-- <option>Pilih Peran</option> -->
                                  <!-- Tambahkan opsi secara dinamis -->
                                <!-- </select> -->
                              </td>
                              <td class="text-center">
                                <button type="button" class="btn btn-danger mt-0 mb-0" onclick="deleteRow(this)">
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
      cell2.innerHTML = `<input type="text" name="mahasiswa[]" class="form-control">`;

      cell3.innerHTML = `<input type="text" name="peran[]" class="form-control">`;

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
      cell2.innerHTML = `<input type="text" name="pembimbing[]" class="form-control">`;

      cell3.innerHTML = `<input type="text" name="pembimbing_peran[]" class="form-control">`;

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