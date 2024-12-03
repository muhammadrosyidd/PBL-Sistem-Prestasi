<?php
    // Koneksi ke database
    $connect = mysqli_connect("localhost", "root", "", "prestasi");
    // Periksa koneksi
    if (mysqli_connect_errno()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
?>