<?php
require 'phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once __DIR__ . '/../../config/Connection.php'; 

// Fungsi untuk menampilkan preview data dalam tabel
function showPreviewData($conn, $tanggal_awal, $tanggal_akhir) {
    $query = "SELECT 
                p.prestasi_id AS 'No',
                p.judul AS 'Nama Kompetisi',
                p.tempat AS 'Lokasi Lomba',
                p.link_kompetisi AS 'Link Kompetisi',
                p.tanggal_mulai AS 'Tanggal Mulai',
                p.tanggal_akhir AS 'Tanggal Akhir',
                p.jumlah_peserta AS 'Jumlah Peserta',
                k.nama_kategori AS 'Kategori',
                t.nama_tingkat AS 'Tingkat Lomba',
                r.nama_peringkat AS 'Peringkat',
                p.verifikasi_status AS 'Status Verifikasi',
                d.nomor_surat_tugas AS 'Nomor Surat Tugas',
                d.komentar AS 'Komentar Dokumen',
                m.nim AS 'NIM Mahasiswa',
                m.nama_depan + ' ' + m.nama_belakang AS 'Nama Mahasiswa',
                pma.nama_peran AS 'Peran Mahasiswa',
                ds.nama AS 'Nama Dosen',
                ds.telepon AS 'Telepon Dosen',
                dpm.nama_peran AS 'Peran Dosen',
                p.tanggal_input AS 'Tanggal Input'
              FROM prestasi p
              JOIN kategori k ON p.kategori_id = k.kategori_id
              JOIN tingkatLomba t ON p.tingkat_lomba_id = t.tingkat_lomba_id
              JOIN peringkat r ON p.peringkat_id = r.peringkat_id
              JOIN dokumen d ON p.dokumen_id = d.dokumen_id
              JOIN presma ps ON p.prestasi_id = ps.prestasi_id
              JOIN mahasiswa m ON ps.nim = m.nim
              JOIN peran_mahasiswa pma ON ps.peran_mahasiswa_id = pma.peran_mahasiswa_id
              JOIN dospem dp ON p.prestasi_id = dp.prestasi_id
              JOIN dosen ds ON dp.dosen_id = ds.dosen_id
              JOIN peran_dosen dpm ON dp.peran_dosen_id = dpm.peran_dosen_id
              WHERE p.tanggal_input BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        echo "<table class='table align-items-center justify-content-center mb-0'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>No</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Nama Kompetisi</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Lokasi Lomba</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Link Kompetisi</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Tanggal Mulai</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Tanggal Akhir</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Jumlah Peserta</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Kategori</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Tingkat Lomba</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Peringkat</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Status Verifikasi</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Nomor Surat Tugas</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Komentar Dokumen</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>NIM Mahasiswa</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Nama Mahasiswa</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Peran Mahasiswa</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Nama Dosen</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Telepon Dosen</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Peran Dosen</th>";
        echo "<th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Tanggal Input</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>{$row['No']}</td>";
            echo "<td>{$row['Nama Kompetisi']}</td>";
            echo "<td>{$row['Lokasi Lomba']}</td>";
            echo "<td>{$row['Link Kompetisi']}</td>";
            echo "<td>{$row['Tanggal Mulai']}</td>";
            echo "<td>{$row['Tanggal Akhir']}</td>";
            echo "<td>{$row['Jumlah Peserta']}</td>";
            echo "<td>{$row['Kategori']}</td>";
            echo "<td>{$row['Tingkat Lomba']}</td>";
            echo "<td>{$row['Peringkat']}</td>";
            echo "<td>{$row['Status Verifikasi']}</td>";
            echo "<td>{$row['Nomor Surat Tugas']}</td>";
            echo "<td>{$row['Komentar Dokumen']}</td>";
            echo "<td>{$row['NIM Mahasiswa']}</td>";
            echo "<td>{$row['Nama Mahasiswa']}</td>";
            echo "<td>{$row['Peran Mahasiswa']}</td>";
            echo "<td>{$row['Nama Dosen']}</td>";
            echo "<td>{$row['Telepon Dosen']}</td>";
            echo "<td>{$row['Peran Dosen']}</td>";
            echo "<td>{$row['Tanggal Input']}</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Tidak ada data untuk ditampilkan.";
    }
}

// Mengecek apakah tombol "Unduh Laporan" telah diklik
if (isset($_POST['unduh'])) {
    $tanggal_awal = isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : '';
    $tanggal_akhir = isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : '';

    if ($tanggal_awal && $tanggal_akhir) {
        $db = new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI"); // Ganti dengan informasi koneksi Anda
        $conn = $db->connect();

        if (!$conn) {
            die("Connection failed: " . print_r(sqlsrv_errors(), true));
        }

        $query = "SELECT 
                    p.prestasi_id AS 'No',
                    p.judul AS 'Nama Kompetisi',
                    p.tempat AS 'Lokasi Lomba',
                    p.link_kompetisi AS 'Link Kompetisi',
                    p.tanggal_mulai AS 'Tanggal Mulai',
                    p.tanggal_akhir AS 'Tanggal Akhir',
                    p.jumlah_peserta AS 'Jumlah Peserta',
                    k.nama_kategori AS 'Kategori',
                    t.nama_tingkat AS 'Tingkat Lomba',
                    r.nama_peringkat AS 'Peringkat',
                    p.verifikasi_status AS 'Status Verifikasi',
                    d.nomor_surat_tugas AS 'Nomor Surat Tugas',
                    d.komentar AS 'Komentar Dokumen',
                    m.nim AS 'NIM Mahasiswa',
                    m.nama_depan + ' ' + m.nama_belakang AS 'Nama Mahasiswa',
                    pma.nama_peran AS 'Peran Mahasiswa',
                    ds.nama AS 'Nama Dosen',
                    ds.telepon AS 'Telepon Dosen',
                    dpm.nama_peran AS 'Peran Dosen'
                  FROM prestasi p
                  JOIN kategori k ON p.kategori_id = k.kategori_id
                  JOIN tingkatLomba t ON p.tingkat_lomba_id = t.tingkat_lomba_id
                  JOIN peringkat r ON p.peringkat_id = r.peringkat_id
                  JOIN dokumen d ON p.dokumen_id = d.dokumen_id
                  JOIN presma ps ON p.prestasi_id = ps.prestasi_id
                  JOIN mahasiswa m ON ps.nim = m.nim
                  JOIN peran_mahasiswa pma ON ps.peran_mahasiswa_id = pma.peran_mahasiswa_id
                  JOIN dospem dp ON p.prestasi_id = dp.prestasi_id
                  JOIN dosen ds ON dp.dosen_id = ds.dosen_id
                  JOIN peran_dosen dpm ON dp.peran_dosen_id = dpm.peran_dosen_id
                  WHERE p.tanggal_input BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Jika data ditemukan, buat file Excel
        if ($data) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header untuk kolom
            $headers = array_keys($data[0]);
            $sheet->fromArray($headers, NULL, 'A1');

            // Isi data
            $sheet->fromArray($data, NULL, 'A2');

            // Mengatur nama file
            $filename = "Laporan_Prestasi_" . date('Ymd') . ".xlsx";

            // Output file Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } else {
            echo "Tidak ada data untuk ditampilkan.";
        }
    } else {
        echo "Silakan pilih tanggal awal dan tanggal akhir.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets2/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets2/img/jti.png">
    <title>Laporan</title>
    <!--     Fonts and icons     -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../../assets2/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-gradient-warning position-absolute w-100"></div>
    <?php
    include_once __DIR__ . '/../layout/sidebarSuper.php';
    ?>
    <main class="main-content position-relative border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 style="text-align: center;">Unduh Laporan</h6>
                                </div>
                                <form method="POST" action="">
                                    <div class="col-md-12" style="padding-left: 2.2rem; padding-right: 2.2rem;">
                                        <div class="form-group">
                                            <label for="tanggal_awal">Tanggal Awal:</label>
                                            <input name="tanggal_awal" id="tanggal_awal" class="form-control" type="date" value="<?= isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-left: 2.2rem; padding-right: 2.2rem;">
                                        <div class="form-group">
                                            <label for="tanggal_akhir">Tanggal Akhir:</label>
                                            <input name="tanggal_akhir" id="tanggal_akhir" class="form-control" type="date" value="<?= isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : '' ?>">
                                        </div>
                                        <button class="btn bg-gradient-warning" type="submit" name="preview" value="Preview">
                                            <i class="fas fa-eye"></i> Tampilkan Preview
                                        </button>
                                        <button class="btn bg-gradient-warning" type="submit" name="unduh" value="Filter">
                                            <i class="fas fa-arrow-down"></i> Unduh Laporan
                                        </button>
                                    </div>

                                </form>



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
                                        <?php
                                        if (isset($_POST['preview']) || isset($_POST['unduh'])) {
                                            if (isset($_POST['tanggal_awal']) && isset($_POST['tanggal_akhir'])) {
                                                $tanggal_awal = $_POST['tanggal_awal'];
                                                $tanggal_akhir = $_POST['tanggal_akhir'];

                                                $db = new Connection("LAPTOP-PUB4O093", "", "", "PRESTASI"); // Ganti dengan informasi koneksi Anda
                                                $conn = $db->connect();

                                                if (!$conn) {
                                                    die("Connection failed: " . print_r(sqlsrv_errors(), true));
                                                }

                                                showPreviewData($conn, $tanggal_awal, $tanggal_akhir); // Panggil fungsi showPreviewData
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </>
        </nav>
    </main>
</body>

</html>