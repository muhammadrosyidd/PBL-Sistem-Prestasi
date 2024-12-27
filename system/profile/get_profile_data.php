<?php
session_start();
require_once __DIR__ . '/../../config/ConnectionPDO.php';

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Session expired']);
    exit;
}

$username = $_SESSION['username'];

try {
    $stmt = $conn->prepare("SELECT * FROM superadmin WHERE username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($userData);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>