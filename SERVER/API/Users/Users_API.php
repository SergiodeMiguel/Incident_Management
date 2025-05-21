<?php
/*
   PHP API endpoint to fetch all users from the database.
   Returns a JSON response containing a success status and an array of user objects.
   Each user object includes the user's ID and name.

   There are 2 files to obtain the users, but I have separated them so as not to 
   generate problems between the edition and the creation of the incident
*/

// Set the response header to JSON format
header('Content-Type: application/json');

// Include the database connection file
require_once '../../Includes/db_connection.php';

// SQL query to get user IDs and names from the "users" table
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);

// Initialize an empty array to hold users
$users = [];

// If query returned results, fetch all users into the array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Return a JSON response with success status and the data array
echo json_encode([
    "success" => true,
    "data" => $users
]);

// Close the database connection
$conn->close();
