<?php
/*
   PHP API endpoint to fetch all departments from the database.
   Returns a JSON response containing a success status and an array of department objects.
   Each department object includes the department's ID and name.

   There are 2 files to obtain the departments, but I have separated them so as not to 
   generate problems between the edition and the creation of the incident
*/

// Set the response header to JSON format
header('Content-Type: application/json');

// Include the database connection file
require_once '../../Includes/db_connection.php';

// SQL query to get department IDs and names from the "departments" table
$sql = "SELECT id, name FROM departments";
$result = $conn->query($sql);

// Initialize an empty array to hold departments
$departments = [];

// If query returned results, fetch all departments into the array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Return a JSON response with success status and the data array
echo json_encode([
    "success" => true,
    "data" => $departments
]);

// Close the database connection
$conn->close();
