<?php
/* Get_Latest_Incidents.php
   Fetches the latest 5 incidents, including human-readable names for users, categories, departments, etc.
   Returns a JSON array of incidents for display on the frontend.
*/

// Include the database connection file
require_once '../../Includes/db_connection.php';

// Set the response content type to JSON
header('Content-Type: application/json');

// SQL query to retrieve the latest 5 incidents with full descriptive info using JOINs
$sql = "SELECT 
            i.id, 
            i.title, 
            i.description, 
            i.creation_date,
            i.status,
            c.name AS category,
            d.name AS department,
            reported_by_user.name AS reported_by,
            assigned_user.name AS assigned_to
        FROM incidents i
        JOIN users reported_by_user ON i.user_id = reported_by_user.id
        LEFT JOIN users assigned_user ON i.assigned_user_id = assigned_user.id
        JOIN categories c ON i.category_id = c.id
        JOIN departments d ON i.department_id = d.id
        ORDER BY i.creation_date DESC
        LIMIT 5";

// Prepare the statement to prevent SQL injection
if ($stmt = $conn->prepare($sql)) {
    // Execute the prepared statement
    $stmt->execute();

    // Bind the result fields to PHP variables (must match SELECT)
    $stmt->bind_result($id, $title, $description, $creation_date, $status, $category, $department, $reported_by, $assigned_to);

    // Prepare an array to store the fetched incidents
    $incidents = [];

    // Fetch each row
    while ($stmt->fetch()) {
        // Format the date to something frontend-friendly (e.g., YYYY-MM-DD)
        $formatted_date = date('Y-m-d', strtotime($creation_date));

        // Push each incident as an associative array
        $incidents[] = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'reported_by' => $reported_by,         // Name of the user who created the incident
            'category' => $category,               // Name of the category
            'department' => $department,           // Name of the department
            'assigned_to' => $assigned_to,         // Name of the assigned user
            'status' => $status,                   // Current status of the incident
            'creation_date' => $formatted_date     // Formatted creation date
        ];
    }

    // Close the prepared statement
    $stmt->close();

    // Return the data as JSON
    echo json_encode($incidents);
} else {
    // If the statement fails to prepare, return a 500 and show error
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query: ' . $conn->error]);
}

// Close the database connection
$conn->close();
?>