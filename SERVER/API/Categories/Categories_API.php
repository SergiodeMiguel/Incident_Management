<?php
/*
   PHP API endpoint to fetch all categories from the database.
   Returns a JSON response containing a success status and an array of category objects.
   Each category object includes the category's ID and name.
   
   There are 2 files to obtain the categories, but I have separated them so as not to 
   generate problems between the edition and the creation of the incident
*/

// Set the response header to JSON format
header('Content-Type: application/json');

// Include the database connection file
require_once '../../Includes/db_connection.php';

// SQL query to get category IDs and names from the "categories" table
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);

// Initialize an empty array to hold categories
$categories = [];

// If query returned results, fetch all categories into the array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Return a JSON response with success status and the data array
echo json_encode([
    "success" => true,
    "data" => $categories
]);

// Close the database connection
$conn->close();
