<?php
/*
  LOGIN API ENDPOINT
  This script processes login requests sent via POST as JSON.
  It verifies the user's username ('user') and password against the database,
  uses password hashing for security,
  manages session creation upon successful login,
  and returns JSON responses indicating success or failure.
*/

// Set response header to JSON as this API returns JSON responses
header('Content-Type: application/json');

// Include the database connection file to use $conn
require_once '../../Includes/db_connection.php';

// Start or resume the session to store user login state
session_start();

// Get raw POST data and decode JSON into associative array
$data = json_decode(file_get_contents('php://input'), true);

// Validate input existence for 'user' and 'password'
if (!$data || !isset($data['user']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing credentials']);
    exit; // Stop execution if data is missing
}

// Sanitize user input by trimming whitespace and escaping special chars for SQL safety
$user = $conn->real_escape_string(trim($data['user']));

// Password is kept as-is for verification
$password = $data['password'];

// Prepare SQL query to select user data by username ('user')
$sql = "SELECT id, name, password, role_id FROM users WHERE name = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // Handle SQL prepare errors

    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

// Bind user parameter as string to the prepared statement
$stmt->bind_param("s", $user);

// Execute the prepared statement
$stmt->execute();

// Fetch the result set from the executed statement
$result = $stmt->get_result();

if ($userData = $result->fetch_assoc()) {
    // If user found with given username ('name' column)

    if (password_verify($password, $userData['password'])) {
        // Verify the plain password against the stored hashed password

        // Store user info in session variables for maintaining login state
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['role_id'] = $userData['role_id'];

        // Return success response with some user info (optional)
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $userData['id'],
                'name' => $userData['name'],
                'role_id' => $userData['role_id']
            ]
        ]);
    } else {
        // Password did not match
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    // No user found with the given username
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

// Close statement and connection to free resources
$stmt->close();
$conn->close();
