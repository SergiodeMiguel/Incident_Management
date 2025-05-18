<?php
/* FILE TO GET DEPARTMENTS
   This file returns a list of all departments (IT, Support, Development, etc.)
   to fill in the "Department" field of the Incident Creation Form.*/

// Specify that the server will return a JSON response
header('Content-Type: application/json');

// Include the script that creates the database connection
require_once '../../Includes/db_connection.php';

// SQL statement to fetch department IDs and names from the "departments" table
$sql = "SELECT id, name FROM departments";

// Execute the query and store the result
$result = $conn->query($sql);

// Initialize an empty array to hold the department data
$departments = [];

// If there are rows in the result, process each one
if ($result && $result->num_rows > 0) {
    // Iterate through each row in the result set
    while ($row = $result->fetch_assoc()) {
        // Add the department information to the array
        $departments[] = $row;
    }
}

// Convert the departments array to JSON format and output it
echo json_encode($departments);

// Terminate the connection to the database
$conn->close();
