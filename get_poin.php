<?php
$use_driver = 'sqlsrv'; // Pilihan driver ('mysql' untuk MySQL, 'sqlsrv' untuk SQL Server)
$host = "localhost"; // Host database
$username = ''; // Username database
$password = ''; // Password database
$database = 'PRESTASI'; // Nama database

$db = null; // Inisialisasi variabel koneksi

// Membuka koneksi ke SQL Server
if ($use_driver == 'sqlsrv') {
    try {
        // Koneksi ke SQL Server
        $connectionInfo = array("Database"=>$database, "UID"=>$username, "PWD"=>$password);
        $db = sqlsrv_connect($host, $connectionInfo);

        if( !$db ) {
            die('Connection to database failed: ' . print_r(sqlsrv_errors(), true));
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

// Query data SQL Server
$sql = "
SELECT 
    m.nim,
    m.nama_depan,
    m.nama_belakang,
    SUM(tl.poin_tingkat + prk.poin_peringkat) AS total_poin
FROM 
    mahasiswa m
JOIN 
    presma p ON m.nim = p.nim
JOIN 
    prestasi pr ON p.idpres = pr.idpres
JOIN 
    tingkatLomba tl ON pr.tingkat_lomba_id = tl.tingkat_lomba_id
JOIN 
    peringkat prk ON pr.peringkat_id = prk.peringkat_id
GROUP BY 
    m.nim, m.nama_depan, m.nama_belakang
ORDER BY 
    total_poin DESC";

// Menjalankan query
$result = sqlsrv_query($db, $sql);

if ($result === false) {
    echo "Error: " . print_r(sqlsrv_errors(), true);
} else {
    $rank = 1;
    if (sqlsrv_has_rows($result)) {
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $playerClass = ($rank === 1) ? 'player-name1' : 'player-name';

            // Menentukan kelas medali berdasarkan peringkat
            $medalClass = '';
            if ($rank === 1) {
                $medalClass = 'medal-gold';
            } elseif ($rank === 2) {
                $medalClass = 'medal-silver';
            } elseif ($rank === 3) {
                $medalClass = 'medal-bronze';
            } else {
                $medalClass = 'medal';
            }

            // Menampilkan data pemain
            echo '<div class="player">
                    <div class="player-info">
                        <div class="medal ' . $medalClass . '"><i class="fas fa-medal"></i></div>
                        <div class="' . $playerClass . '">' . $row['nama_depan'] . ' ' . $row['nama_belakang'] . ' - ' . $row['nim'] . '</div>
                    </div>
                    <div class="score">' . $row['total_poin'] . '</div>
                  </div>';
            $rank++;
        }
    } else {
        echo "Tidak ada data.";
    }
}

// Menutup koneksi
sqlsrv_close($db);
?>
