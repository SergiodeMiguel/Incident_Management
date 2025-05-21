<?php
// Set the response header to JSON format
header('Content-Type: application/json');

// Include the database connection file (adjusted to your project structure)
require_once '../../Includes/db_connection.php';

// Check if the 'id' parameter is provided in the GET request
if (!isset($_GET['id'])) {
  // Return JSON indicating missing ID error
  echo json_encode([
    'success' => false,
    'message' => 'ID not provided.'
  ]);
  exit; // Stop further execution
}

// Sanitize the 'id' parameter to integer
$id = intval($_GET['id']);

// $conn comes from db_connection.php
// Prepare the SQL query to select incident by ID
$sql = "SELECT 
          id,
          title,
          description,
          status,
          category_id,
          department_id,
          user_id,
          assigned_user_id
        FROM incidents
        WHERE id = ?";

// Initialize prepared statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
  // If prepare fails, send error JSON response
  echo json_encode([
    'success' => false,
    'message' => 'Prepare statement failed.',
    'error' => $conn->error
  ]);
  exit;
}

// Bind the parameter as integer
$stmt->bind_param("i", $id);

// Execute the prepared statement
$stmt->execute();

// Get the result set from the executed statement
$result = $stmt->get_result();

// Fetch the incident as an associative array
$incident = $result->fetch_assoc();

if ($incident) {
  // If incident found, return it with success true
  echo json_encode([
    'success' => true,
    'data' => $incident
  ]);
} else {
  // If no incident found, return an error message
  echo json_encode([
    'success' => false,
    'message' => 'Incident not found.'
  ]);
}

// Close statement and connection
$stmt->close();
$conn->close();
