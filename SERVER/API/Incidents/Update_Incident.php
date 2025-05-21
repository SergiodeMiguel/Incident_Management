<?php
/*
   PHP API endpoint to update an existing incident in the database.
   It receives incident data via a JSON POST request,
   validates and sanitizes input,
   updates the incident record securely using prepared statements,
   and returns a JSON response indicating success or failure.
*/
// Set the response header to JSON since this script returns JSON data
header('Content-Type: application/json'); 

require_once '../../Includes/db_connection.php'; 
// Include the database connection script to use $conn (mysqli connection)

// Get the raw POST data sent as JSON and decode it into a PHP associative array
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
  // Check if the decoded data is valid and contains the 'id' field
  // If not valid, respond with an error message and stop execution
  echo json_encode(['success' => false, 'message' => 'Invalid input']);
  exit;
}

// Sanitize and assign variables from the input data for security and use
$id = intval($data['id']); 
// Convert id to integer to prevent injection and ensure type safety

$title = $conn->real_escape_string(trim($data['title'] ?? ''));
// Trim whitespace and escape special characters to safely use in SQL

$description = $conn->real_escape_string(trim($data['description'] ?? ''));
// Same for description

$status = $conn->real_escape_string($data['status'] ?? '');
// Escape status field (expected to be a string from a fixed set of values)

$category_id = intval($data['category_id'] ?? 0);
// Convert category_id to integer, defaulting to 0 if not set

$department_id = intval($data['department_id'] ?? 0);
// Same for department_id

$user_id = intval($data['user_id'] ?? 0);
// Same for user_id (reported_by)

$assigned_user_id = isset($data['assigned_user_id']) ? intval($data['assigned_user_id']) : null;
// assigned_user_id can be null, so check existence before converting to int

// Prepare the SQL update statement with placeholders for secure parameter binding
$sql = "UPDATE incidents SET
          title = ?,
          description = ?,
          status = ?,
          category_id = ?,
          department_id = ?,
          user_id = ?,
          assigned_user_id = ?
        WHERE id = ?";

// Prepare the SQL statement, checking if preparation succeeds
$stmt = $conn->prepare($sql);

if (!$stmt) {
  // If preparation fails, output error message and exit
  echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
  exit;
}

// Bind the variables to the prepared statement parameters with correct types:
// 'sssiiiii' means string, string, string, int, int, int, int, int (types for each ?)
$stmt->bind_param(
  'sssiiiii',
  $title,
  $description,
  $status,
  $category_id,
  $department_id,
  $user_id,
  $assigned_user_id,
  $id
);

// Execute the prepared statement and check if it succeeds
if ($stmt->execute()) {
  // If successful, return JSON success response
  echo json_encode(['success' => true, 'message' => 'Incident updated successfully']);
} else {
  // If execution fails, return JSON error response with statement error
  echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

// Close the statement and the database connection to free resources
$stmt->close();
$conn->close();
