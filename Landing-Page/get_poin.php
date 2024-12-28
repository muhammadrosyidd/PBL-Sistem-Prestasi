<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prestasi";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

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
    prestasi pr ON p.prestasi_id = pr.prestasi_id
JOIN 
    tingkatLomba tl ON pr.tingkat_lomba_id = tl.tingkat_lomba_id
JOIN 
    peringkat prk ON pr.peringkat_id = prk.peringkat_id
GROUP BY 
    m.nim, m.nama_depan, m.nama_belakang
ORDER BY 
    total_poin DESC
LIMIT 10;";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $rank = 1;
        while ($row = $result->fetch_assoc()) {

            $playerClass = ($rank === 1) ? 'player-name1' : 'player-name';


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

$conn->close();
?>