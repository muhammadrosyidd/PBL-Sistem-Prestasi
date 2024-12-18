<?php
require_once __DIR__ . '/../config/Connection.php'; // Path to your database connection file

$db = new Connection("localhost", "", "", "PRESTASI"); // Replace with your database credentials
$conn = $db->connect();

// Fetch prestasi data for the past 12 months
$currentYear = date('Y');
$chartData = [];
$chartLabels = [];

for ($i = 0; $i < 12; $i++) {
    $month = date('m', strtotime("-$i months"));
    $monthName = date('M', strtotime("-$i months")); // Short month name (e.g., Jan, Feb)
    $year = date('Y', strtotime("-$i months"));

    $query = "SELECT COUNT(*) AS prestasi_count FROM prestasi WHERE MONTH(tanggal_input) = ? AND YEAR(tanggal_input) = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$month, $year]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $prestasiCount = $result['prestasi_count'];

    $chartData[] = $prestasiCount;
    $chartLabels[] = $monthName . " " . $year; //Adding the year for clarity
}

// Reverse the arrays to show the months in chronological order (most recent first)
$chartData = array_reverse($chartData);
$chartLabels = array_reverse($chartLabels);


// Encode data as JSON for use in JavaScript
$chartDataJson = json_encode($chartData);
$chartLabelsJson = json_encode($chartLabels);

?>