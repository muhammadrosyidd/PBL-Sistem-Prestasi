<?php
class Connection {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Constructor untuk menginisialisasi parameter koneksi database
    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    // Method untuk membuka koneksi menggunakan PDO
    public function connect() {
        try {
            // Membuka koneksi PDO ke SQL Server.  Perubahan ada di baris ini
            $this->conn = new PDO("sqlsrv:Server=$this->servername;Database=$this->dbname;ConnectionPooling=0", $this->username, $this->password);
            // Set error mode PDO ke exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }

        return $this->conn;
    }

    // Method untuk menutup koneksi
    public function close() {
        $this->conn = null;
    }

    // Getter untuk mendapatkan koneksi
    public function getConnection() {
        return $this->conn;
    }
}

// Membuat instance dan membuka koneksi. Perubahan ada di baris ini
$db = new Connection("localhost", "", "", "PRESTASI"); // Servername diubah menjadi localhost
$conn = $db->connect();

?>