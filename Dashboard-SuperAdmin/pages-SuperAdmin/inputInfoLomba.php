<?php 
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "DAYDREAMER"; // 'localhost'
$username = ''; // 'sa'
$password = ''; 
$database = 'PencatatanPrestasi'; 
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
    $jenisLomba = $_POST['jenisLomba'];
    $tingkatLomba = $_POST['tingkatLomba'];
    $tanggalPelaksanaan = $_POST['tanggalPelaksanaan'];
    $linkPendaftaran = $_POST['linkPendaftaran'];
    $penyelenggara = $_POST['penyelenggara'];
    $targetFile = $_POST['posterLomba'];

    // SQL untuk memasukkan data
    $query = "INSERT INTO informasiLomba (posterLomba, jenisLomba, tingkatLomba, tanggalPelaksanaan, linkPendaftaran, penyelenggara)
              VALUES ('$targetFile', '$jenisLomba', '$tingkatLomba', '$tanggalPelaksanaan', '$linkPendaftaran', '$penyelenggara')";

    if ($use_driver == 'mysql') {
        if ($db->query($query) === TRUE) {
            header("Location: informasiLomba.php"); // Redirect ke informasiLomba.php setelah berhasil
            exit();
        } else {
            echo "Error: " . $db->error;
        }
    } else if ($use_driver == 'sqlsrv') {
        $stmt = sqlsrv_query($db, $query);
        if ($stmt) {
            header("Location: informasiLomba.php"); // Redirect ke informasiLomba.php setelah berhasil
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
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/jti.png">
    <link rel="icon" type="image/png" href="../assets/img/jti.png">
    <title>
        Input Informasi Lomba
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
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
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
                <img src="../assets/img/jti.png" width="30px" height="50px" class="navbar-brand-img h-100"
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
                    <a class="nav-link " href="../pages-SuperAdmin/dataPengguna.php">
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
                    <a class="nav-link active" href="informasiLomba.php">
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
        <nav
            class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2 mt-n11">
            <div class="container-fluid py-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 ps-2 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="text-white opacity-5"
                                href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Profile</li>
                    </ol>
                    <h6 class="text-white font-weight-bolder ms-2">Profile</h6>
                </nav>
                <div class="me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search"
                                    aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0">
                                <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line bg-white"></i>
                                        <i class="sidenav-toggler-line bg-white"></i>
                                        <i class="sidenav-toggler-line bg-white"></i>
                                    </div>
                                </a>
                            </a>
                        </li>
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 ms-n4"
                                aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New message</span> from Laur
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    13 minutes ago
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/small-logos/logo-spotify.svg"
                                                    class="avatar avatar-sm bg-gradient-dark me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New album</span> by Travis Scott
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    1 day
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <title>credit-card</title>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-2169.000000, -745.000000)"
                                                            fill="#FFFFFF" fill-rule="nonzero">
                                                            <g transform="translate(1716.000000, 291.000000)">
                                                                <g transform="translate(453.000000, 454.000000)">
                                                                    <path class="color-background"
                                                                        d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                        opacity="0.593633743"></path>
                                                                    <path class="color-background"
                                                                        d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    Payment successfully completed
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    2 days
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="card shadow-lg mx-4 card-profile-bottom">
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Input Informasi Lomba</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <p class="text-uppercase text-sm">User Information</p> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" action="prosesInfoLomba.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="posterLomba">Poster Lomba</label>
                                            <input class="form-control" type="file" id="posterLomba" name="posterLomba" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenisLomba">Jenis Lomba</label>
                                            <input class="form-control" type="text" id="jenisLomba" name="jenisLomba" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tingkatLomba">Tingkat Lomba</label>
                                            <input class="form-control" type="text" id="tingkatLomba" name="tingkatLomba" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggalPelaksanaan">Tanggal Pelaksanaan</label>
                                            <input class="form-control" type="date" id="tanggalPelaksanaan" name="tanggalPelaksanaan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="linkPendaftaran">Link Pendaftaran</label>
                                            <input class="form-control" type="url" id="linkPendaftaran" name="linkPendaftaran" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="penyelenggara">Penyelenggara</label>
                                            <input class="form-control" type="text" id="penyelenggara" name="penyelenggara" required>
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
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>