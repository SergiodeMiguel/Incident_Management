<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response content type to JSON
header('Content-Type: application/json');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Include the database connection
require_once '../../Includes/db_connection.php';

try {
    // Check if the request method is DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Decode the JSON input to get the incident ID
        $data = json_decode(file_get_contents("php://input"));
        $incidentId = isset($data->id) ? $data->id : null; // Retrieve ID from JSON data

        // Check if ID is valid
        if ($incidentId) {
            // SQL query to delete the incident with the given ID
            $sql = "DELETE FROM incidents WHERE id = ?";

            // Initialize the statement
            $stmt = $conn->prepare($sql);

            // Check if the statement was prepared successfully
            if ($stmt === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to prepare the SQL query.' // If preparing SQL failed
                ]);
                exit;
            }

            // Bind the ID parameter (the 'i' means integer)
            $stmt->bind_param('i', $incidentId);

            // Execute the query
            $stmt->execute();

            // Check if a row was affected (i.e., deletion was successful)
            if ($stmt->affected_rows > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Incident deleted successfully.' // Success message if deletion is successful
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Incident not found or could not be deleted.' // Error message if no rows are affected
                ]);
            }

            // Close the statement
            $stmt->close();
        } else {
            // If no valid ID is provided
            echo json_encode([
                'success' => false,
                'message' => 'Invalid ID.' // Invalid ID error message
            ]);
        }
    } else {
        // If the request method is not DELETE
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method.' // Error if the request method is incorrect
        ]);
    }
} catch (Exception $e) {
    // Catch any exceptions and display error
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting incident: ' . $e->getMessage() // General error message if something goes wrong
    ]);
}
?>
