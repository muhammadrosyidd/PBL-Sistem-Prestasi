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
        // Modified to use array for localhost connection
        $connInfo = array("Database"=>$this->dbname, "UID"=>$this->username, "PWD"=>$this->password);
        $this->conn = sqlsrv_connect($this->servername, $connInfo);


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

// Create a new instance of DatabaseConnection and connect.  Modified to use localhost
$db = new Connection("localhost", "", "", "PRESTASI"); // servername diubah menjadi localhost
$conn = $db->connect();
?>