<?php 
/* ALL_INCIDENTS.PHP – Backend script to fetch and return all incidents
   This script retrieves all incident records from the database, including related data 
   such as categories, departments, and users (reported by and assigned to).
   It returns the data as a JSON response to be consumed by the frontend (All_Incidents.js).
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response content type to JSON
header('Content-Type: application/json');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include the database connection (mysqli)
require_once '../../Includes/db_connection.php';

try {
    // SQL query to get all incidents with related data
    $sql = "SELECT 
                i.id, 
                i.title, 
                i.status, 
                i.description, 
                c.name AS category,
                d.name AS department,
                u1.name AS reported_by,
                u2.name AS assigned_to,
                i.creation_date AS created_at
            FROM incidents i
            LEFT JOIN categories c ON i.category_id = c.id
            LEFT JOIN departments d ON i.department_id = d.id
            LEFT JOIN users u1 ON i.user_id = u1.id
            LEFT JOIN users u2 ON i.assigned_user_id = u2.id
            ORDER BY i.creation_date DESC";

    // Use mysqli to execute the query
    $result = $conn->query($sql);

    if ($result) {
        // Fetch all incidents
        $incidents = $result->fetch_all(MYSQLI_ASSOC);

        // Return the incidents as JSON
        echo json_encode([
            'success' => true,
            'data' => $incidents
        ]);
    } else {
        // If the query fails
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching incidents'
        ]);
    }
} catch (Exception $e) {
    // Handle exception and return error message
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching incidents',
        'error' => $e->getMessage()
    ]);
}
