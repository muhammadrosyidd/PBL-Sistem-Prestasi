<?php
$use_driver = 'sqlsrv'; // atau 'mysql'
$host = "localhost"; // 'localhost'
$username = ''; // 'sa'
$password = '';
$database = 'PRESTASI';
$db;

// Cek koneksi
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

// Query untuk mengambil data dari tabel informasiLomba
$sql = "SELECT * FROM infolomba";
$result = sqlsrv_query($db, $sql);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
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
        Informasi Lomba
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

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
    <?php
  include_once __DIR__ . '/../layout/sidebarSuper.php';
  ?>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Informasi Lomba</h6>
                            <a href="inputInfoLomba.php"><button class="btn bg-gradient-warning">+ Informasi</button></a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Poster Lomba</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Lomba</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tingkat Lomba</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pelaksanaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Link Pendaftaran</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penyelenggara</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1; // Initialize row number
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td class='text-center text-xxs font-weight-bold mb-0'>{$no}</td>";
                                            echo "<td class='text-center'><img src='{$row['gambar_poster']}' style='width: 100px;' alt='Poster Lomba'></td>";
                                            echo "<td class='text-center text-xs font-weight-bold mb-0'>{$row['jenis_lomba']}</td>";
                                            echo "<td class='align-middle text-center'><span class='text-secondary text-xs font-weight-bold'>{$row['tingkat_lomba_id']}</span></td>";
                                            echo "<td class='text-center text-xxs font-weight-bold mb-0'>{$row['tanggal_pelaksanaan']->format('d F Y')}</td>";
                                            echo "<td class='text-center text-xs font-weight-bold mb-0'>{$row['link_pendaftaran']}</td>";
                                            echo "<td class='text-center text-xs font-weight-bold mb-0'>{$row['penyelenggara']}</td>";
                                            echo "<td class='align-middle text-center text-sm'>
                                                        <button type='button' class='btn bg-gradient-primary' onclick=\"window.location.href='editInfoLomba.php?id_infoLomba={$row['id_infoLomba']}'\">Edit</button>
                                                        <form action='hapusLomba.php' method='POST' style='display:inline;' onsubmit='return confirmDelete();'>
                                                            <input type='hidden' name='link_pendaftaran' value='{$row['link_pendaftaran']}'>
                                                             <button type='submit' class='btn bg-gradient-danger' >Hapus</button>
                                                        </form>
                                                    </td>";
                                            echo "</tr>";
                                            $no++; // Increment row number
                                        }
                                        ?>
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
    <script>
    function confirmDelete() {
      return confirm("Apakah Anda yakin ingin menghapus Informasi ini?");
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
<?php
// Menutup koneksi
if ($use_driver == 'mysql') {
    $db->close();
} else if ($use_driver == 'sqlsrv') {
    sqlsrv_close($db);
}
?>