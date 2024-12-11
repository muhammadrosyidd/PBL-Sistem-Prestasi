<?php
require_once __DIR__ . '/../../config/Connection.php';

// Memeriksa apakah data POST ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data untuk tabel prestasi
    $nimMahasiswa = $_POST['nimMahasiswa'];
    $peran = $_POST['peran'];
    $judulKompetisi = $_POST['judulKompetisi'];
    $tempatKompetisi = $_POST['tempatKompetisi'];
    $tingkatKompetisi = $_POST['tingkatKompetisi'];
    $linkKompetisi = $_POST['linkKompetisi'];
    $jumlahPeserta = $_POST['jumlahPeserta'];
    $peringkatJuara = $_POST['peringkatJuara'];
    $tanggalMulai = $_POST['tanggalMulai'];
    $tanggalAkhir = $_POST['tanggalAkhir'];
    $noSuratTugas = $_POST['noSuratTugas'];
    $tanggalSuratTugas = $_POST['tanggalSuratTugas'];

    // Data untuk Mahasiswa dan Pembimbing
    $mahasiswa = isset($_POST['mahasiswa']) ? $_POST['mahasiswa'] : [];
    $peranMahasiswa = isset($_POST['peran']) ? $_POST['peran'] : [];
    $pembimbing = isset($_POST['pembimbing']) ? $_POST['pembimbing'] : [];
    $peranPembimbing = isset($_POST['pembimbing_peran']) ? $_POST['pembimbing_peran'] : [];

    // Mengatur file upload
    $uploadDir = 'uploads/';
    
    // Inisialisasi variabel file
    $fileSuratTugasName = null;
    $fileSertifikatName = null;
    $fotoKegiatanName = null;

    // Memeriksa dan mengupload file jika ada
    if (isset($_FILES['fileSuratTugas']) && $_FILES['fileSuratTugas']['error'] == 0) {
        $fileSuratTugasName = $uploadDir . basename($_FILES['fileSuratTugas']['name']);
        move_uploaded_file($_FILES['fileSuratTugas']['tmp_name'], $fileSuratTugasName);
    }

    if (isset($_FILES['fileSertifikat']) && $_FILES['fileSertifikat']['error'] == 0) {
        $fileSertifikatName = $uploadDir . basename($_FILES['fileSertifikat']['name']);
        move_uploaded_file($_FILES['fileSertifikat']['tmp_name'], $fileSertifikatName);
    }

    if (isset($_FILES['fotoKegiatan']) && $_FILES['fotoKegiatan']['error'] == 0) {
        $fotoKegiatanName = $uploadDir . basename($_FILES['fotoKegiatan']['name']);
        move_uploaded_file($_FILES['fotoKegiatan']['tmp_name'], $fotoKegiatanName);
    }

    // Pastikan koneksi database valid
    $conn = (new Connection("DESKTOP-IVR2LTO", "", "", "PRESTASI"))->connect();

    if ($conn) {
        // Query untuk menyimpan data prestasi ke database
        $queryPrestasi = "INSERT INTO prestasi (nim_mahasiswa, peran, judul_kompetisi, tempat_kompetisi, tingkat_kompetisi, link_kompetisi, jumlah_peserta, peringkat_juara, tanggal_mulai, tanggal_akhir, no_surat_tugas, tanggal_surat_tugas, file_surat_tugas, file_sertifikat, foto_kegiatan) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Persiapkan statement
        $stmtPrestasi = sqlsrv_prepare($conn, $queryPrestasi, [
            &$nimMahasiswa, &$peran, &$judulKompetisi, &$tempatKompetisi, &$tingkatKompetisi, &$linkKompetisi,
            &$jumlahPeserta, &$peringkatJuara, &$tanggalMulai, &$tanggalAkhir, &$noSuratTugas, &$tanggalSuratTugas,
            &$fileSuratTugasName, &$fileSertifikatName, &$fotoKegiatanName
        ]);

        // Eksekusi query untuk prestasi
        if (sqlsrv_execute($stmtPrestasi)) {
            // Jika prestasi berhasil dimasukkan, simpan data Mahasiswa dan Pembimbing
            $prestasiId = sqlsrv_insert_id($conn); // ID dari prestasi yang baru disimpan

            // Menyimpan data mahasiswa terkait prestasi
            if (!empty($mahasiswa)) {
                foreach ($mahasiswa as $index => $mhs) {
                    $queryMahasiswa = "INSERT INTO mahasiswa_prestasi (prestasi_id, mahasiswa_id, peran) VALUES (?, ?, ?)";
                    $stmtMahasiswa = sqlsrv_prepare($conn, $queryMahasiswa, [
                        &$prestasiId, &$mhs, &$peranMahasiswa[$index]
                    ]);
                    sqlsrv_execute($stmtMahasiswa);
                }
            }

            // Menyimpan data pembimbing terkait prestasi
            if (!empty($pembimbing)) {
                foreach ($pembimbing as $index => $bimbing) {
                    $queryPembimbing = "INSERT INTO pembimbing_prestasi (prestasi_id, pembimbing_id, peran) VALUES (?, ?, ?)";
                    $stmtPembimbing = sqlsrv_prepare($conn, $queryPembimbing, [
                        &$prestasiId, &$bimbing, &$peranPembimbing[$index]
                    ]);
                    sqlsrv_execute($stmtPembimbing);
                }
            }

            // Redirect atau beri pesan sukses
            echo "Prestasi berhasil disimpan!";
        } 
    } else {
        echo "Error: Database connection failed.";
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
              <form action="" method="POST">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nimMahasiswa" class="form-control-label">NIM Mahasiswa</label>
                      <input class="form-control" type="text" name="nimMahasiswa" id="nimMahasiswa" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="peran" class="form-control-label">Peran</label>
                        <input class="form-control" value="Ketua Kelompok" name="peran" id="peran" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="judulKompetisi" class="form-control-label">Judul Kompetisi</label>
                      <input class="form-control" type="text" name="judulKompetisi" id="judulKompetisi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tempatKompetisi" class="form-control-label">Tempat Kompetisi</label>
                      <input class="form-control" type="text" name="tempatKompetisi" id="tempatKompetisi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                        <label for="tingkatKompetisi">Tingkat Kompetisi</label>
                        <select class="form-control" name="tingkatKompetisi" required>
                            <option value="Regional">Regional</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="linkKompetisi" class="form-control-label">Link Kompetisi</label>
                      <input class="form-control" type="text" name="linkKompetisi" id="linkKompetisi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="jumlahPeserta" class="form-control-label">Jumlah Peserta</label>
                      <input class="form-control" type="number" name="jumlahPeserta" id="jumlahPeserta" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                        <label for="peringkatJuara">Peringkat</label>
                        <select class="form-control" name="peringkatJuara" required>
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
                      <label for="tanggalMulai" class="form-control-label">Tanggal Mulai</label>
                      <input class="form-control" type="date" name="tanggalMulai" id="tanggalMulai" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggalAkhir" class="form-control-label">Tanggal Akhir</label>
                      <input class="form-control" type="date" name="tanggalAkhir" id="tanggalAkhir" required>
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
                                                  <select name="mahasiswa[]" class="form-control">
                                                      <option>Pilih Mahasiswa</option>
                                                      <!-- Tambahkan opsi secara dinamis dari database -->
                                                  </select>
                                              </td>
                                              <td class="text-center">
                                                  <select name="peran[]" class="form-control">
                                                      <option>Pilih Peran</option>
                                                      <!-- Tambahkan opsi secara dinamis -->
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
                                                  <select name="pembimbing[]" class="form-control">
                                                      <option>Pilih Pembimbing</option>
                                                      <!-- Tambahkan opsi secara dinamis -->
                                                  </select>
                                              </td>
                                              <td class="text-center">
                                                  <select name="pembimbing_peran[]" class="form-control">
                                                      <option>Pilih Peran</option>
                                                      <!-- Tambahkan opsi secara dinamis -->
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