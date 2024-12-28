<?php
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "localhost"; // 'localhost'
$username = ''; // 'sa'
$password = '';
$database = 'PRESTASI';
$db;

if ($use_driver == 'mysql') {
    try {
        $db = new mysqli($host, $username, $password, $database);

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
    $jenis_lomba = $_POST['jenis_lomba'];
    $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];
    $gambar_poster = $_FILES['gambar_poster']['name']; // Ubah dari $_POST ke $_FILES untuk gambar_poster

    // Validasi tanggal_pelaksanaan
    if (strtotime($tanggal_pelaksanaan) < strtotime(date("Y-m-d"))) {
        echo "Tanggal pelaksanaan harus lebih besar atau sama dengan hari ini.";
        exit();
    }

    // Handle file upload for gambar_poster
    if ($_FILES['gambar_poster']['error'] == UPLOAD_ERR_OK) {
        // Validasi file gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['gambar_poster']['type'], $allowed_types)) {
            echo "File poster harus berupa gambar (JPG, PNG, GIF).";
            exit();
        }

        // Cek ukuran file
        if ($_FILES['gambar_poster']['size'] > 2 * 1024 * 1024) { // Maksimal 2MB
            echo "File poster terlalu besar. Maksimal ukuran file adalah 2MB.";
            exit();
        }

        // Tentukan path upload
        $upload_path = "Poster Lomba/" . $_FILES['gambar_poster']['name'];
        if (!move_uploaded_file($_FILES['gambar_poster']['tmp_name'], $upload_path)) {
            echo "Gagal mengunggah file poster.";
            exit();
        }
    }

    // SQL untuk memasukkan data dengan prepared statement (untuk MySQL)
    if ($use_driver == 'mysql') {
        $stmt = $db->prepare("INSERT INTO infolomba (gambar_poster, jenis_lomba, tingkat_lomba_id, tanggal_pelaksanaan, link_pendaftaran, penyelenggara)
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $gambar_poster, $jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara);

        if ($stmt->execute()) {
            header("Location: dataInformasiLomba.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    // SQL untuk memasukkan data dengan prepared statement (untuk SQL Server)
    else if ($use_driver == 'sqlsrv') {
        $query = "INSERT INTO infolomba (gambar_poster, jenis_lomba, tingkat_lomba_id, tanggal_pelaksanaan, link_pendaftaran, penyelenggara)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$gambar_poster, $jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara];

        $stmt = sqlsrv_query($db, $query, $params);

        if ($stmt) {
            header("Location: dataInformasiLomba.php");
            exit();
        } else {
            $msg = sqlsrv_errors();
            echo "Error: " . $msg[0]['message'];
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
        Input Informasi Lomba
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/tambahmhs.css">
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

        <div class="card shadow-lg mx-4 card-profile-bottom">
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0" style="font-weight: bold;">Input Informasi Lomba</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" action="prosesInfoLomba.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="gambar_poster">Poster Lomba</label>
                                            <input class="form-control" type="file" id="gambar_poster"
                                                name="gambar_poster" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_lomba">Jenis Lomba</label>
                                            <input class="form-control" type="text" id="jenis_lomba" name="jenis_lomba"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tingkat_lomba_id">Tingkat Lomba</label>
                                            <select class="form-control" id="tingkat_lomba_id" name="tingkat_lomba_id"
                                                required>
                                                <option value="">Pilih Tingkat Lomba</option>
                                                <option value="1">Provinsi</option>
                                                <option value="2">Nasional</option>
                                                <option value="3">Internasional</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan</label>
                                            <input class="form-control" type="date" id="tanggal_pelaksanaan"
                                                name="tanggal_pelaksanaan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="link_pendaftaran">Link Pendaftaran</label>
                                            <input class="form-control" type="url" id="link_pendaftaran"
                                                name="link_pendaftaran" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="penyelenggara">Penyelenggara</label>
                                            <input class="form-control" type="text" id="penyelenggara"
                                                name="penyelenggara" required>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-sm">Submit</button>
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