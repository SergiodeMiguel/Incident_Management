<?php
/*
I have made the database connection configuration in a separate file so that I don't have to repeat the connection manually in each one.
I avoid duplication of code and it is easy to maintain, since the configuration is only changed here.
*/

// ---------- DATABASE CONNECTION ----------

// Define database connection credentials
$host = 'localhost';
$db = 'incident_managementdb';
$user = 'root'; // Change this if your user is different
$pass = '';     // Add your password if needed

// Create a new connection to the MySQL database
$conn = new mysqli($host, $user, $pass, $db);

// Check if connection has failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>