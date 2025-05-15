<?php
// Redirect back to the form if accessed directly without the ?success=1 parameter
if (!isset($_GET['success']) || $_GET['success'] != 1) {
    header("Location: create_incident.php");
    exit();
}
?>
<?php
// Include the connection to the database from db_connection.php
require_once 'C:\xampp\htdocs\PROYECT\SERVER\Includes\db_connection.php'; // Adjust the path if needed

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

    // ---------- PREPARED STATEMENT TO INSERT INCIDENT ----------

    /* Prepare the SQL The prepare() function creates a statement with markers (?)
       which will be replaced later with real data.
       This prevents SQL injection attacks by separating SQL logic from data*/

    $statement = $conn->prepare("INSERT INTO incidents (
                                title, description, user_id, assigned_user_id, 
                                category_id, department_id, status
                            ) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if the statement preparation succeeded
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

        // Execute the prepared statement
        if ($statement->execute()) {
            // Redirect on success
            header("Location: Success_Incident.php?success=1"); 
            exit();
        } else {
            // If execution fails, show error
            echo "Error executing statement: " . $statement->error;
        }

        // Close the statement
        $statement->close();
    } else {
        // If preparation fails, show error
        echo "Failed to prepare statement: " . $conn->error;
    }
} else {
    // If required fields are missing
    echo "All fields are required.";
}

// Close the database connection
$conn->close();
?>
