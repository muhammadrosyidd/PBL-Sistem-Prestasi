<?php
require_once 'config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = getDatabaseConnection();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username AND password = HASHBYTES('SHA2_256', :password)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
