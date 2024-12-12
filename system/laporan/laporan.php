<?php
require_once __DIR__ . '/../../config/Connection.php'; // Pastikan Anda telah menginstal PHPSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Query untuk mengambil data laporan dari database
    $sql = "SELECT id AS No, tanggal AS Tanggal, nama_kompetisi AS NamaKompetisi, jenis AS Jenis FROM laporan";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Memasukkan data dari database ke dalam array
    $laporanData = [["No", "Tanggal", "Nama Kompetisi", "Jenis"]]; // Header kolom
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $laporanData[] = [$row['No'], $row['Tanggal']->format('Y-m-d'), $row['NamaKompetisi'], $row['Jenis']];
    }

    // Membuat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan data ke dalam sheet
    foreach ($laporanData as $rowIndex => $rowData) {
        foreach ($rowData as $colIndex => $cellData) {
            $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $cellData);
        }
    }

    // Set header untuk pengunduhan file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan.xlsx"');
    header('Cache-Control: max-age=0');

    // Menggunakan Xlsx untuk menulis file Excel
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
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
        Laporan
    </title>
    <!--     Fonts and icons     -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
    <?php
    include_once __DIR__ . '/../layout/sidebarSuper.php';
    ?>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">

            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6 style="text-align: center;">Unduh Laporan</h6>
                        </div>

                        <div style="padding-left: 2.2rem; padding-right: 2.2rem;" class="col-md-12">
                            <div class="form-group">
                                <h5>Pilih Tanggal</h5>

                                <label for="tanggal mulai">Tanggal Awal</label>
                                <input id="tanggalAwal" class="form-control" type="date">
                            </div>
                        </div>
                        <div style="padding-left: 2.2rem; padding-right: 2.2rem;" class="col-md-12">
                            <div class="form-group">

                                <label for="tanggal mulai">Tanggal Akhir</label>
                                <input id="tanggalAkhir" class="form-control" type="date">
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <a style="padding-left: 2.2rem;" href="#"><button class="btn bg-gradient-warning"><i class="fas fa-arrow-down"></i> Unduh Laporan</button></a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6 style="text-align: center;">Preview Laporan Data</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center justify-content-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kompetisi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis</th>


                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="laporanTabel">
                                        <!-- Data akan diisi melalui JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        </div>

        <script>
            // Data dummy untuk laporan
            const laporanData = [{
                    no: 1,
                    tanggal: '2024-12-01',
                    nama: 'John Doe',
                    prestasi: 'Juara 1 Lomba Coding'
                },
                {
                    no: 2,
                    tanggal: '2024-12-02',
                    nama: 'Jane Smith',
                    prestasi: 'Juara 2 Hackathon'
                },
                {
                    no: 3,
                    tanggal: '2024-12-03',
                    nama: 'Alice Johnson',
                    prestasi: 'Juara 3 Desain UI'
                }
            ];

            // Event listener untuk input tanggal
            document.getElementById('tanggalAwal').addEventListener('change', filterLaporan);
            document.getElementById('tanggalAkhir').addEventListener('change', filterLaporan);

            function filterLaporan() {
                const tanggalAwal = document.getElementById('tanggalAwal').value;
                const tanggalAkhir = document.getElementById('tanggalAkhir').value;
                const laporanTabel = document.getElementById('laporanTabel');

                // Bersihkan tabel
                laporanTabel.innerHTML = '';

                // Filter data berdasarkan tanggal
                if (tanggalAwal && tanggalAkhir) {
                    const filteredData = laporanData.filter(data =>
                        data.tanggal >= tanggalAwal && data.tanggal <= tanggalAkhir
                    );

                    // Tampilkan data ke tabel
                    if (filteredData.length > 0) {
                        filteredData.forEach(item => {
                            const row = `<tr>
                            <td style="margin: 0.5rem;" class="text-center text-xs font-weight-bold">${item.no}</td>
                            <td class=" text-xs font-weight-bold">${item.tanggal}</td>
                            <td class=" text-xs font-weight-bold">${item.nama}</td>
                            <td class=" text-xs font-weight-bold">${item.prestasi}</td>
                        </tr>`;
                            laporanTabel.innerHTML += row;
                        });
                    } else {
                        laporanTabel.innerHTML = '<tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>';
                    }
                }
            }
        </script>
    </main>

    <!--   Core JS Files   -->
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