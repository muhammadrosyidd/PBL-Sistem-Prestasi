<?php
function getDatabaseConnection() {
    $serverName = "localhost"; // Atau nama server SQL Server Anda
    $database = "your_database"; // Nama database
    $username = "your_username"; // Username SQL Server
    $password = "your_password"; // Password SQL Server

    try {
        // Koneksi menggunakan PDO
        $dsn = "sqlsrv:server=$serverName;Database=$database";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
