<?php
class Connection {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Constructor to initialize database connection parameters
    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    // Method to establish the connection
    public function connect() {
        $this->conn = sqlsrv_connect($this->servername, array(
            "UID" => $this->username,
            "PWD" => $this->password,
            "Database" => $this->dbname,
        ));

        // Check connection
        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        return $this->conn;
    }

    // Method to close the connection
    public function close() {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }

    // Getter for connection
    public function getConnection() {
        return $this->conn;
    }
}

// Create a new instance of DatabaseConnection and connect
$db = new Connection("DESKTOP-IVR2LTO", "", "", "PRESTASI");
$conn = $db->connect();
?>