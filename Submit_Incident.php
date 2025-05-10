<?php
// Include the connection to the database from db_connection.php
require_once 'Includes/db.php'; // Set the path if it is in another folder

// Validar que todos los campos han sido enviados
if (
    isset($_POST['title'], $_POST['description'], $_POST['user'], 
          $_POST['category'], $_POST['department'], $_POST['status'])
) {
    // Recoger y limpiar los datos del formulario
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $user_id = (int)$_POST['user'];
    $category_id = (int)$_POST['category'];
    $department_id = (int)$_POST['department'];
    $status = $conn->real_escape_string($_POST['status']);

    // Preparar consulta SQL para insertar el incidente
    $sql = "INSERT INTO incidents (title, description, user_id, category_id, department_id, status) 
            VALUES ('$title', '$description', $user_id, $category_id, $department_id, '$status')";

    // Ejecutar la consulta y comprobar resultado
    if ($conn->query($sql) === TRUE) {
        // Redirigir al formulario con éxito
        header("Location: indice2   .php?success=1");
        exit();
    } else {
        echo "Error inserting incident: " . $conn->error;
    }
} else {
    echo "All fields are required.";
}

// Cerrar conexión
$conn->close();
?>
