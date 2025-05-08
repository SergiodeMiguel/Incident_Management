<?php
// Database connection configuration
$host = 'localhost';
$db = 'incident_managementdb';
$user = 'root'; // Cambia esto si tu usuario es diferente
$pass = '';     // Añade la contraseña si tienes una

// Conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
