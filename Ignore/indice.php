<?php
// ---------- DATABASE CONNECTION ----------

// Define database connection credentials
$host = 'localhost';
$db = 'incident_managementdb';
$user = 'root'; // Change this if your MySQL user is different
$pass = '';     // Add your password if your MySQL user has one

// Create a new connection to the MySQL database
$conn = new mysqli($host, $user, $pass, $db);

// Check if connection has failed
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error); // Stop script and show error message
}
// ---------- FETCH DATA FROM DATABASE ----------

// Query to get users for the "User" dropdown
$users = $conn->query("SELECT id, name FROM users");

// Query to get categories for the "Category" dropdown
$categories = $conn->query("SELECT id, name FROM categories");

// Query to get departments for the "Department" dropdown
$departments = $conn->query("SELECT id, name FROM departments");

// Fetch existing incidents for display
$incidents = $conn->query(
  "SELECT i.id, i.title, i.description, i.status, i.creation_date, 
  u.name as user_name, c.name as category_name, d.name as department_name 
  FROM incidents i JOIN users u ON i.user_id = u.id 
  JOIN categories c ON i.category_id = c.id 
  JOIN departments d ON i.department_id = d.id 
  ORDER BY i.creation_date DESC");

// Note: Status options are hardcoded in this version, not fetched from the database.
// You can modify this to fetch from a "statuses" table if needed. 

// Check for success message
// $success_message = isset($_GET['success']) ? "Incident successfully added!" : "";

// ---------- HTML FORM ----------
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Incident Management</title> <!-- Page title shown in browser tab -->
  <link rel="stylesheet" href="styles.css" /> <!-- Link to external CSS file for styling -->
</head>

<body>
  <!-- Wrapper container for layout and spacing -->
  <div class="container">

    <!-- Main heading of the page -->
    <h1>Incident Management System</h1>

    <!-- Form for submitting a new incident -->
    <!-- "method=POST" sends data securely; "action" points to the PHP script that processes the form: "submit_incident.php"  -->
    <form id="incident-form" method="POST" action="submit_incident.php">

    <!-- Text input for the title of the incident -->
      <label for="title">Title</label>
      <input type="text" id="title" name="title" required />

    <!-- Text area for a description of the incident -->
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" required></textarea>

    <!-- Dropdown to select the user reporting the incident (loaded from the database) -->
      <label for="user">User</label>
      <select id="user" name="user" required>
        <?php while($row = $users->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <!-- Categories dropdown from DB -->
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <?php while($row = $categories->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <!-- Departments dropdown from DB -->
      <label for="department">Department</label>
      <select id="department" name="department" required>
        <?php while($row = $departments->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <!-- Static statuses for now -->
      <label for="status">Status</label>
      <select id="status" name="status" required>
        <option value="Open">Open</option>
        <option value="In Progress">In Progress</option>
        <option value="Resolved">Resolved</option>
      </select>

      <button type="submit">Add Incident</button>
      <!-- Optional: Reset button to clear the form -->
      <button type="reset">Reset</button>

    </form>
  </div>
</body>
</html>
