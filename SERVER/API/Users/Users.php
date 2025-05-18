<?php
            /* FILE TO GET USER DATA
               This file returns a list of all registered users in the database.
               It is used to fill in the "Reported By" and "Assigned To" fields of the form.*/

// Return a JSON response with all users from the database
header('Content-Type: application/json');

// Include DB connection
require_once '../../Includes/db_connection.php';

// SQL query to retrieve user IDs and names from the "users" table
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql); // Execute the query and store the result in the variable $result

// Array to store user data
$users = [];

// Check if the query returns rows ( if there are any users in the database)
// If the query was successful and returned rows, fetch the data and store it in the $users array
if ($result && $result->num_rows > 0) {
    // Loop through each row returned by the query
    while ($row = $result->fetch_assoc()) { 
        $users[] = $row; // Append the row (which is an associative array) to the $users array
    }
}

// Convert the $users array to JSON format and send it as a response to the client
echo json_encode($users);

// Close the database connection
$conn->close();
