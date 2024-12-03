<?php
    include "loginConnection.php";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username='$username' and password='$password'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if ($row['role'] == 'admin') {
            // Redirect ke halaman dashboard.html setelah login
            header("Location: \dasarWeb\PBL-Sistem-Prestasi\Dashboard-Admin\pages-Admin\dashboard.html");
            exit();
        } elseif ($row['role'] == 'superadmin') {
            // Redirect ke halaman dashboard.html setelah login
            header("Location: \dasarWeb\PBL-Sistem-Prestasi\Dashboard-SuperAdmin\pages-SuperAdmin\dashboard.html");
            exit();
        }
    } else {
        // Jika login gagal, redirect ke login form dengan error
        header("Location: sign-in.html?error=1");
        exit();
    }
?>