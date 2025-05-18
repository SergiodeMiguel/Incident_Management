<?php
/* FILE TO OBTAIN THE CATEGORIES OF THE INCIDENTS
   This file returns a list of all available categories (Hardware, Software, etc.)
   that will be used to fill in the "Category" field of the issue creation form.*/

// Set the content type of the response to JSON format
header('Content-Type: application/json');

// Include the file that sets up the database connection
require_once '../../Includes/db_connection.php';

// Define the SQL query to get category IDs and names from the "categories" table
$sql = "SELECT id, name FROM categories";

// Execute the SQL query and save the result
$result = $conn->query($sql);

// Create an empty array to store the category data
$categories = [];

// If the query returned results, process each row
if ($result && $result->num_rows > 0) {
    // Loop through each row returned by the database
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row; // Add the category (associative array) to the categories array
    }
}

// Encode the categories array into a JSON string and return it as the response
echo json_encode($categories);

// Close the connection to the database
$conn->close();
