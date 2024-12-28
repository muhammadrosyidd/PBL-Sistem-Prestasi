<?php
$use_driver = 'mysql'; // mysql atau sqlsrv 
$host = "localhost"; //'localhost'; 
$username = 'root'; //'sa'; 
$password = '';
$database = 'prestasi';
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

$id_infolomba;
// Fetch user data if available for editing
if (isset($_GET['id_infoLomba'])) {
    $id_infolomba = $_GET['id_infoLomba'];

    // Buat query SQL
    $query = "SELECT * FROM infolomba WHERE id_infoLomba = ?";
    
    // Persiapkan statement
    $stmt = $db->prepare($query);
    if ($stmt) {
        // Bind parameter
        $stmt->bind_param("i", $id_infolomba); // "i" menunjukkan tipe integer
        
        // Eksekusi statement
        $stmt->execute();
        
        // Ambil hasil query
        $result = $stmt->get_result();
        
        // Fetch data dari hasil query
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $jenis_lomba = $row['jenis_lomba'];
            $tingkat_lomba_id = $row['tingkat_lomba_id'];
            $tanggal_pelaksanaan = $row['tanggal_pelaksanaan'];
            $link_pendaftaran = $row['link_pendaftaran'];
            $penyelenggara = $row['penyelenggara'];
            $gambar_poster = $row['gambar_poster'];
        } else {
            // Handle the case where no record is found
            $jenis_lomba = '';
            $tingkat_lomba_id = '';
            $tanggal_pelaksanaan = '';
            $link_pendaftaran = '';
            $penyelenggara = '';
            $gambar_poster = '';
        }
        
        // Tutup statement
        $stmt->close();
    } else {
        echo "Error dalam mempersiapkan query: " . $conn->error;
    }
}


// Handle the form submission for updating competition data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $jenis_lomba = $_POST['jenis_lomba'];
    $tingkat_lomba_id = $_POST['tingkat_lomba_id'];
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];

    // $id_now = $_GET['id_infoLomba'];
    // var_dump($id_now);
    // die();


    // Handle file upload for gambar_poster
    if (isset($_FILES['gambar_poster']) && $_FILES['gambar_poster']['error'] == UPLOAD_ERR_OK) {
        $gambar_poster = $_FILES['gambar_poster']['name'];
        move_uploaded_file($_FILES['gambar_poster']['tmp_name'], "Poster Lomba/" . $gambar_poster);
    } else {
        // Handle the case where no file was uploaded or there was an error
        $gambar_poster = ''; // or handle it as needed
    }

    // Update data competition
    $update_lomba_query = "UPDATE infolomba SET jenis_lomba = ?, tingkat_lomba_id = ?, tanggal_pelaksanaan = ?, link_pendaftaran = ?, penyelenggara = ?, gambar_poster = ? WHERE id_infoLomba = ?";
    $stmt_update_lomba = $db->prepare($update_lomba_query);
    if ($stmt_update_lomba) {
        // Bind parameters
        $stmt_update_lomba->bind_param("iissssi", $jenis_lomba, $tingkat_lomba_id, $tanggal_pelaksanaan, $link_pendaftaran, $penyelenggara, $gambar_poster, $id_infoLomba);
        
        // Execute the update
        if ($stmt_update_lomba->execute()) {
            echo "Data updated successfully.";
            
        } else {
            echo "Error updating data: " . $stmt_update_lomba->error;
        }
        // Close the statement
        $stmt_update_lomba->close();
    } else {
        echo "Error preparing update query: " . $db->error;
    }

    // Redirect ke halaman informasiLomba.php setelah berhasil
    header("Location: informasiLomba.php");
    exit(); // Pastikan script dihentikan setelah redirect
}
// Menyelesaikan output buffering
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/jti.png">
    <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
    <title>
        Update Pengguna
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
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0"
                href=" https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html " target="_blank">
                <img src="../../assets2/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100"
                    alt="main_logo">
                <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="../pages-SuperAdmin/dashboard.html">
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
                        <form action="editInfoLomba.php" method="POST" enctype="multipart/form-data">
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