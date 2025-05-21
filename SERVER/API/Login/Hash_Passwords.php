<?php
/*
  PASSWORD HASHING SCRIPT
  This script is intended to be run once to convert all plain text passwords 
  stored in the 'users' table into secure hashed passwords using PHP's password() function.
  
  It:
  - Fetches all users and their current 'password' field (which currently holds plain passwords)
  - Checks if the password is already hashed (to avoid double hashing)
  - Hashes the plain password securely
  - Updates the user record with the hashed password
  - Outputs status messages for each user processed
*/

// Include the database connection file to use $conn
require_once '../../Includes/db_connection.php'; // Adjust path if needed

// Query to select all users with their current passwords (plain or hashed)
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

while ($user = $result->fetch_assoc()) {
    $id = $user['id'];
    $plainPassword = $user['password']; // Currently holds the password, possibly plain text

    // Skip if password already appears hashed (optional check)
    if (strpos($plainPassword, '$2y$') === 0 || strpos($plainPassword, '$argon2') === 0) {
        echo "Usuario ID $id ya tiene contraseña hasheada. Saltando.\n";
        continue;
    }

    // Hash the plain text password securely
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Prepare update statement to save hashed password back to DB
    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $updateStmt->bind_param("si", $hashedPassword, $id);

    if ($updateStmt->execute()) {
        echo "Contraseña hasheada para usuario ID $id\n";
    } else {
        echo "Error actualizando usuario ID $id: " . $conn->error . "\n";
    }

    $updateStmt->close();
}

$conn->close();
?>
