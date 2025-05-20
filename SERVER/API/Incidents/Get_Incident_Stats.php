<?php
/* Get_Incident_Stats.php
   Returns the total count of open and closed incidents for dashboard statistics.
   Incidents with status 'Open' or 'In Progress' are considered open.
   Incidents with status 'Closed' are considered closed.
*/

// Include the database connection file
require_once '../../Includes/db_connection.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = [
    'open' => 0,
    'closed' => 0
];

// SQL query to count incidents with status 'Open' or 'In Progress'
$sql_open = "SELECT COUNT(*) FROM incidents WHERE status = 'Open' OR status = 'In Progress'";

// Execute query for open incidents
if ($stmt_open = $conn->prepare($sql_open)) {
    $stmt_open->execute();
    $stmt_open->bind_result($open_count);
    $stmt_open->fetch();
    $response['open'] = $open_count;
    $stmt_open->close();
} else {
        // If statement preparation fails, return error and exit
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query for open incidents: ' . $conn->error]);
    exit;
}

// SQL query to count incidents with status 'Closed'
$sql_closed = "SELECT COUNT(*) FROM incidents WHERE status = 'Closed'";

// Execute query for closed incidents
if ($stmt_closed = $conn->prepare($sql_closed)) {
    $stmt_closed->execute();
    $stmt_closed->bind_result($closed_count);
    $stmt_closed->fetch();
    $response['closed'] = $closed_count;
    $stmt_closed->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query for closed incidents: ' . $conn->error]);
    exit;
}

// Return the result as JSON
echo json_encode($response);

// Close the database connection
$conn->close();
?>
