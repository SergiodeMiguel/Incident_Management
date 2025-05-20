<?php
/* Get_Incident_Stats.php
   This script retrieves the count of open and closed incidents from the database
   and returns it as a JSON response. */

// Include database connection
require_once '../../Includes/db_connection.php'; // Adjust path if necessary

// Set response header to JSON so client interprets it correctly
header('Content-Type: application/json');

// Prepare the SQL queries to count open and closed incidents
// We use prepared statements even if no parameters for consistency and security

// Query to count open incidents
$openIncidents = "SELECT COUNT(*) AS open_count FROM incidents WHERE status = 'Open'";
// Query to count closed incidents
$closedIncidents = "SELECT COUNT(*) AS closed_count FROM incidents WHERE status = 'Closed'";

// Initialize variables to hold counts
$openCount = 0;
$closedCount = 0;

// Execute the open incidents count query
if ($stmtOpen = $conn->prepare($openIncidents)) {
    // Execute statement
    $stmtOpen->execute();
    // Bind result to variable
    $stmtOpen->bind_result($openCount);
    // Fetch the result
    $stmtOpen->fetch();
    // Close statement to free resources
    $stmtOpen->close();
} else {
    // If statement preparation fails, return error and exit
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare open count query: ' . $conn->error]);
    exit;
}

// Execute the closed incidents count query
if ($stmtClosed = $conn->prepare($closedIncidents)) {
    $stmtClosed->execute();
    $stmtClosed->bind_result($closedCount);
    $stmtClosed->fetch();
    $stmtClosed->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare closed count query: ' . $conn->error]);
    exit;
}

// Return the counts as JSON
echo json_encode([
    'open' => $openCount,
    'closed' => $closedCount
]);

// Close the database connection
$conn->close();
?>
