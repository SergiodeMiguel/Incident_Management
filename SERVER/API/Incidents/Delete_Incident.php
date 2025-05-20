<?php
/* Delete_Incident.php
   This script deletes an incident given its ID.
   It accepts the incident ID via the DELETE method or GET. */

// Include database connection
require_once '../../Includes/db_connection.php';

// Set response header to JSON
header('Content-Type: application/json');

// Detect request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize $id variable
$id = null;

// If method is DELETE, parse input stream for parameters
if ($method === 'DELETE') {
    // Parse raw input
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = isset($delete_vars['id']) ? (int)$delete_vars['id'] : null;
}
// Alternatively, allow GET for testing purposes
elseif ($method === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
} else {
    // If method not allowed, respond with 405 Method Not Allowed
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Validate that ID was provided
if (!$id) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Missing incident ID']);
    exit;
}

// Prepare delete statement
$sql = "DELETE FROM incidents WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    // Bind ID parameter as integer
    $stmt->bind_param("i", $id);
    
    // Execute statement
    if ($stmt->execute()) {
        // Check if any rows were affected (deleted)
        if ($stmt->affected_rows > 0) {
            // Success response
            echo json_encode(['success' => true]);
        } else {
            // Incident not found (no rows deleted)
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Incident not found']);
        }
    } else {
        // Execution error
        http_response_code(500);
        echo json_encode(['error' => 'Execution failed: ' . $stmt->error]);
    }
    
    // Close statement
    $stmt->close();
} else {
    // Statement preparation error
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
}

// Close DB connection
$conn->close();
?>
