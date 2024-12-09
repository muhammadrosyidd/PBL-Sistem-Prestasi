<?php
// Include your database connection file
include('../config/db_connect.php'); 

// Check if NIDN is set in the URL
if (isset($_GET['nidn'])) {
    // Sanitize the NIDN value to prevent any malicious inputs
    $nidn = $_GET['nidn'];

    // Validate NIDN (you can add additional validation if necessary)
    if (empty($nidn)) {
        echo "Error: NIDN is missing.";
        exit();
    }

    // SQL query to delete the professor based on NIDN
    $sql = "DELETE FROM dosen WHERE nidn = ?";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $nidn); // "s" denotes the string data type for NIDN
        
        // Execute the statement
        if ($stmt->execute()) {
            // Check if any row was affected
            if ($stmt->affected_rows > 0) {
                // Redirect back to the dataDosen page
                header("Location: dataDosen.php");
                exit();
            } else {
                echo "Error: No matching record found to delete.";
            }
        } else {
            echo "Error: Could not execute the delete query.";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }
} else {
    echo "Error: NIDN parameter is not set.";
}

// Close the database connection
$conn->close();
?>
