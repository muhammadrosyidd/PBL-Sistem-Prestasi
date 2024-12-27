<?php 
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'
$username = ''; // 'sa'
$password = ''; 
$database = 'PRESTASI'; 
$db; 

if ($use_driver == 'mysql') { 
    try { 
        $db = new mysqli('localhost', $username, $password, $database); 
        
        if ($db->connect_error) { 
            die('Connection DB failed: ' . $db->connect_error); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
} else if ($use_driver == 'sqlsrv') { 
    $credential = [ 
        'Database' => $database, 
        'UID' => $username, 
        'PWD' => $password 
    ]; 
    
    try { 
        $db = sqlsrv_connect($host, $credential); 
        
        if (!$db) { 
            $msg = sqlsrv_errors(); 
            die($msg[0]['message']); 
        } 
    } catch (Exception $e) { 
        die($e->getMessage()); 
    } 
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil data dari form
  $nim = $_POST['nim'];
  $judul = $_POST['judul'];
  $tempat = $_POST['tempat'];
  $link_kompetisi = $_POST['link_kompetisi'];
  $jumlah_peserta = $_POST['jumlah_peserta'];
  $tanggal_mulai = $_POST['tanggal_mulai'];
  $tanggal_akhir = $_POST['tanggal_akhir'];
  $nomor_surat_tugas = $_POST['nomor_surat_tugas'];
  $tanggal_surat_tugas = $_POST['tanggal_surat_tugas'];

  // Proses upload file
  $targetDir = "Prestasi/";
  $surat_tugas = $targetDir . uniqid() . '_' . basename($_FILES["surat_tugas"]["name"]);
  $sertifikat = $targetDir . uniqid() . '_' . basename($_FILES["sertifikat"]["name"]);
  $foto_kegiatan = $targetDir . uniqid() . '_' . basename($_FILES["foto_kegiatan"]["name"]);

  // Cek upload file dan simpan ke database
  if (move_uploaded_file($_FILES["surat_tugas"]["tmp_name"], $surat_tugas) &&
      move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $sertifikat) &&
      move_uploaded_file($_FILES["foto_kegiatan"]["tmp_name"], $foto_kegiatan)) {

      // Simpan data ke tabel prestasi
      $sqlPrestasi = "INSERT INTO prestasi (nim, judul_kompetisi, tempat_kompetisi, link_kompetisi, jumlah_peserta, tanggal_mulai, tanggal_akhir, surat_tugas, sertifikat, foto_kegiatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $paramsPrestasi = [$nim, $judul, $tempat, $link_kompetisi, $jumlah_peserta, $tanggal_mulai, $tanggal_akhir, $surat_tugas, $sertifikat, $foto_kegiatan];
      $stmtPrestasi = sqlsrv_query($db, $sqlPrestasi, $paramsPrestasi);

      if ($stmtPrestasi) {
          header("Location: dataPrestasi.php");
      } else {
          echo "Error: " . sqlsrv_errors()[0]['message'];
      }
  } else {
      echo "Maaf, terjadi kesalahan saat mengupload file.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/jti.png">
  <link rel="icon" type="image/png" href="../assets/img/jti.png">
  <title>
    Input Prestasi
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/tambahmhs.css">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-gradient-warning position-absolute w-100">
    <span class="mask bg-gradient-warning opacity-5"></span>
  </div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html " target="_blank">
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
              <form action="prosesPrestasi.php" method="POST" enctype="multipart/form-data">
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
                        <input class="form-control" value="1" name="peran_mahasiswa_id" id="peran_mahasiswa_id" readonly>
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
                                                  <select name="mahasiswa[]" class="form-control">
                                                      <option>Pilih Mahasiswa</option>
                                                      <option value="1">Mahasiswa 1</option>
                                                      <option value="2">Mahasiswa 2</option>
                                                      <option value="3">Mahasiswa 3</option>
                                                      <option value="4">Mahasiswa 4</option>
                                                      <option value="5">Mahasiswa 5</option>
                                                  </select>
                                              </td>
                                              <td class="text-center">
                                                  <select name="peran_mahasiswa_id" class="form-control">
                                                      <option>Pilih Peran</option>
                                                      <option value="1">Anggota</option>
                                                      <option value="2">Personal</option>
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
                                                      <option value="1">Pembimbing 1</option>
                                                      <option value="2">Pembimbing 2</option>
                                                      <option value="3">Pembimbing 3</option>
                                                      <option value="4">Pembimbing 4</option>
                                                      <option value="5">Pembimbing 5</option>
                                                  </select>
                                              </td>
                                              <td class="text-center">
                                                  <select name="peran_dosen_id" class="form-control">
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