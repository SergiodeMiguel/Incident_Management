<?php
// Include the connection to the database from db_connection.php
require_once '../../Includes/db_connection.php'; // Adjust the path if needed

// Set the response header to JSON
// Tells the browser (and the JS client) that the response to be returned by the PHP script is JSON.

header('Content-Type: application/json');

// ---------- VALIDATE AND CLEAN INPUT ----------

// Check that all required fields have been submitted
// The isset() function returns true if all variables exist and are not null.
if (
    isset($_POST['title'], $_POST['description'], $_POST['user'], 
          $_POST['assigned_user'], $_POST['category'], 
          $_POST['department'], $_POST['status'])
) {
// Assign values directly (MySQLi prepared statements will handle escaping)
    /* By escaping data, we ensure that any special characters from the user 
       (such as quotes or semicolons) are treated as plain text 
       and not as SQL commands, thus preventing SQL injection attacks.*/
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = (int)$_POST['user'];
    $assigned_user_id = (int)$_POST['assigned_user'];
    $category_id = (int)$_POST['category'];
    $department_id = (int)$_POST['department'];
    $status = $_POST['status'];

    /// ---------- PREPARED STATEMENT TO INSERT INCIDENT ----------

    /* Prepare the SQL The prepare() function creates a statement with markers (?)
       which will be replaced later with real data.
       This prevents SQL injection attacks by separating SQL logic from data*/

    $statement = $conn->prepare("INSERT INTO incidents (
                                    title, description, user_id, assigned_user_id, 
                                    category_id, department_id, status
                                ) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if statement was prepared successfully
    if ($statement) {
        /* Bind the parameters to the prepared statement
           The bind_param() function binds the variables to the statement as parameters.
           
           'ssiiiis' means:
           s = string, i = integer
           The order of the types must match the order of the placeholders in the SQL statement.*/
        $statement->bind_param("ssiiiis", 
            $title, $description, $user_id, $assigned_user_id, 
            $category_id, $department_id, $status
        );

        // Execute the statement
        if ($statement->execute()) {
            // Send a JSON success response
            echo json_encode(['success' => true]);
        } else {
            // Send an error response with the SQL error
            echo json_encode([
                'success' => false, 
                'message' => 'Execution failed: ' . $statement->error
            ]);
        }

        // Close the prepared statement
        $statement->close();
    } else {
        // If preparation fails, send error response
        echo json_encode([
            'success' => false,
            'message' => 'Statement preparation failed: ' . $conn->error
        ]);
    }
} else {
    // Send error if any required fields are missing
    echo json_encode([
        'success' => false, 
        'message' => 'Missing required fields.'
    ]);
}

// Close the database connection
$conn->close();
?>
