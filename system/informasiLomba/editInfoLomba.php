<?php
$use_driver = 'sqlsrv'; // Use sqlsrv driver
$host = "localhost";
$username = '';
$password = '';
$database = 'PRESTASI';
$db;

// Koneksi ke database
if ($use_driver == 'sqlsrv') {
    $credential = [
        'Database' => $database,
        'UID' => $username,
        'PWD' => $password
    ];

    try {
        $db = sqlsrv_connect($host, $credential);

        if (!$db) {
            $msg = sqlsrv_errors();
            die("Koneksi gagal: " . $msg[0]['message']);
        }
    } catch (Exception $e) {
        die("Koneksi gagal: " . $e->getMessage());
    }
}

$id_infolomba = null;
$jenis_lomba = '';
$tingkat_lomba_id = '';
$tanggal_pelaksanaan = '';
$link_pendaftaran = '';
$penyelenggara = '';
$gambar_poster = '';

// Fetch user data if available for editing
if (isset($_GET['id_infoLomba'])) {
    $id_infolomba = $_GET['id_infoLomba'];

    // Query untuk mendapatkan data
    $query = "SELECT * FROM infolomba WHERE id_infoLomba = ?";
    $params = [$id_infolomba];
    $stmt = sqlsrv_query($db, $query, $params);

    if ($stmt) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            $jenis_lomba = $row['jenis_lomba'];
            $tingkat_lomba_id = $row['tingkat_lomba_id'];
            $tanggal_pelaksanaan = $row['tanggal_pelaksanaan']->format('Y-m-d'); // Format tanggal
            $link_pendaftaran = $row['link_pendaftaran'];
            $penyelenggara = $row['penyelenggara'];
            $gambar_poster = $row['gambar_poster'];
        }
    } else {
        die("Error dalam eksekusi query: " . print_r(sqlsrv_errors(), true));
    }
}

// Validate and update the data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $jenis_lomba = $_POST['jenis_lomba'];
    $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];

    // Validate tanggal_pelaksanaan format (ensure it's a valid date)
   

    // Handle file upload for gambar_poster
    if (isset($_FILES['gambar_poster']) && $_FILES['gambar_poster']['error'] == UPLOAD_ERR_OK) {
        // Check file type (example: only images)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['gambar_poster']['type'], $allowed_types)) {
            die("File poster harus berupa gambar (JPG, PNG, GIF).");
        }

        // Check file size (example: max 2MB)
        if ($_FILES['gambar_poster']['size'] > 2 * 1024 * 1024) {
            die("File poster terlalu besar. Maksimal ukuran file adalah 2MB.");
        }

        // Define upload path and move the file
        $gambar_poster = $_FILES['gambar_poster']['name'];
        $upload_path = "Poster Lomba/" . $gambar_poster;
        if (!move_uploaded_file($_FILES['gambar_poster']['tmp_name'], $upload_path)) {
            die("Gagal mengunggah file poster ke: $upload_path");
        }
    }

    // Update data competition
    $update_lomba_query = "UPDATE infolomba 
                           SET jenis_lomba = ?, tingkat_lomba_id = ?, tanggal_pelaksanaan = ?, link_pendaftaran = ?, penyelenggara = ?, gambar_poster = ? 
                           WHERE id_infoLomba = ?";
    $params_update = [$jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara, $gambar_poster, $id_infolomba];
    $stmt_update_lomba = sqlsrv_query($db, $update_lomba_query, $params_update);

    if ($stmt_update_lomba) {
        $rows_affected = sqlsrv_rows_affected($stmt_update_lomba);
        if ($rows_affected === 0) {
            die("Tidak ada baris yang diperbarui. Periksa apakah data sudah sesuai.");
        }
        // Redirect ke halaman informasiLomba.php setelah berhasil
        header("Location: dataInformasiLomba.php");
        exit(); // Pastikan script dihentikan setelah redirect
    } else {
        die("Error updating data: " . print_r(sqlsrv_errors(), true));
    }
}

// Tutup koneksi database
sqlsrv_close($db);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/jti.png">
    <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
    <title>
        Update Informasi Lomba
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/tambahmhs.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="position-absolute w-100 min-height-300 top-0"
        style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-warning opacity-5"></span>
    </div>
    <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
    <div class="main-content position-relative max-height-vh-100 h-100">
        <!-- Navbar -->

        <!-- End Navbar -->
        
        <div class="container-fluid py-4">
        <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <p class="mb-0">Update Informasi Lomba</p>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jenis_lomba" class="form-control-label">Jenis Lomba</label>
                                        <input class="form-control" type="text" name="jenis_lomba" value="<?php echo htmlspecialchars($jenis_lomba); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tingkat_lomba_id" class="form-control-label">Tingkat Lomba</label>
                                        <select class="form-control" name="tingkat_lomba_id" required>
                                            <option value="1" <?php if ($tingkat_lomba_id == 1) echo 'selected'; ?>>Nasional</option>
                                            <option value="2" <?php if ($tingkat_lomba_id == 2) echo 'selected'; ?>>Internasional</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class=" form-group">
                                        <label for="tanggal_pelaksanaan" class="form-control-label">Tanggal Pelaksanaan</label>
                                        <input class="form-control" type="date" name="tanggal_pelaksanaan" value="<?php echo htmlspecialchars($tanggal_pelaksanaan); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="link_pendaftaran" class="form-control-label">Link Pendaftaran</label>
                                        <input class="form-control" type="url" name="link_pendaftaran" value="<?php echo htmlspecialchars($link_pendaftaran); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="penyelenggara" class="form-control-label">Penyelenggara</label>
                                        <input class="form-control" type="text" name="penyelenggara" value="<?php echo htmlspecialchars($penyelenggara); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="gambar_poster" class="form-control-label">Poster Lomba</label>
                                        <input class="form-control" type="file" name="gambar_poster">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!--   Core JS Files   -->
            <script>
                function addRow() {
                    var table = document.getElementById("mahasiswaTable").getElementsByTagName('tbody')[0];
                    var rowCount = table.rows.length;
                    var row = table.insertRow(rowCount);

                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);

                    cell1.innerHTML = rowCount + 1;
                    cell2.innerHTML = '<select><option>Pilih Mahasiswa</option></select>';
                    cell3.innerHTML = '<select><option>Pilih Peran</option></select>';
                    cell4.innerHTML = '<button class="btn-delete" onclick="deleteRow(this)"><i class="fas fa-times"></i></button>';
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

                    cell1.innerHTML = rowCount + 1;
                    cell2.innerHTML = '<select><option>Pilih Pembimbing</option></select>';
                    cell3.innerHTML = '<select><option>Pilih Peran Pembimbing</option></select>';
                    cell4.innerHTML = '<button class="btn-delete" onclick="deleteRow(this)"><i class="fas fa-times"></i></button>';
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