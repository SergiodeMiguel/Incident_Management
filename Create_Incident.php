<?php
// Include the connection to the database from db_connection.php
require_once 'Includes/db_connection.php'; // Set the path if it is in another folder

// ---------- FETCH DATA FROM DATABASE ----------

// Fetch all users once and store them in an array
$user_result = $conn->query("SELECT id, name FROM users");
$users = [];

if ($user_result) {
  $users = $user_result->fetch_all(MYSQLI_ASSOC); //Converts all results into a complete associative array.
                                                  // This is useful for later use in the form dropdowns.
}

// If you don't want to store it in an array, you can use this code block.
/* Query to get users for the "Reported By" dropdown
$users = $conn->query("SELECT id, name FROM users");

// Query to get users for the "Assigned To" dropdown.
// Re-fetch user list again because when a result is run through with a while, it is exhausted (it is of a single use).
$users_assigned = $conn->query("SELECT id, name FROM users");
*/

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
  <link rel="stylesheet" href="Assets\styles.css" /> <!-- Link to external CSS file for styling -->
</head>

<body>
  <!-- Wrapper container for layout and spacing -->
  <div class="container">

    <!-- Main heading of the page -->
    <h1>Incident Management System</h1>
    <h2>Create New Incident</h2>
    
    <!-- Form for submitting a new incident -->
    <!-- "method=POST" sends data securely; "action" points to the PHP script that processes the form: "submit_incident.php"  -->
    <form id="incident-form" method="POST" action="Submit_Incident.php">

    <!-- Text input for the title of the incident -->
      <label for="title">Title</label>
      <input type="text" id="title" name="title" required />

    <!-- Text area for a description of the incident -->
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" required></textarea>
      <span class="form-hint">Provide detailed information about the incident</span>

    <!-- Dropdown to select the user reporting the incident  -->
      <label for="user">Reported By</label>
      <select id="user" name="user" required>
        <option value="" disabled selected hidden>Select user</option>
        <?php foreach ($users as $user): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
        <?php endforeach; ?>

        <!-- Remove the block above uncomment this block to obtain users in a different way -->
        <?php /*while($row = $users->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile;*/ ?>
      </select>
      <span class="form-hint">Select the person who reports this incident</span>

    <!-- Dropdown to select the user assigned to resolve the incident -->
      <label for="assigned_user">Assigned To</label>
      <select id="assigned_user" name="assigned_user" required>
        <option value="" disabled selected hidden>Select user</option>
        <?php foreach ($users as $user): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
        <?php endforeach; ?>
        
        <!-- Remove the block above uncomment this block to obtain users in a different way -->
        <?php /*while($row = $users_assigned->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile;*/?>
      </select>
      <span class="form-hint">Select the person responsible for handling this incident</span>

      <!-- Categories dropdown from DB -->
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="" disabled selected hidden>Select category</option>
        <?php while($row = $categories->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <span class="form-hint">Select the categorie of the incident</span>

      <!-- Departments dropdown from DB -->
      <label for="department">Department</label>
      <select id="department" name="department" required>
        <option value="" disabled selected hidden>Select department</option>
        <?php while($row = $departments->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <span class="form-hint">Select the department which is affected</span>

      <!-- Static status for now -->
      <label for="status">Status</label>
      <select id="status" name="status" required>
        <option value="" disabled selected hidden>Select status</option>
        <option value="Open">Open</option>
        <option value="In Progress">In Progress</option>
        <option value="Resolved">Resolved</option>
      </select>
      <span class="form-hint">Select the person status of the incident</span>

      <!-- Submit button to send the information to "Submit_Incident.php" -->
      <button type="submit">Add Incident</button>
      <!-- Reset button to clear the form -->
      <button type="reset">Reset</button>

    </form>
  </div>
</body>
</html>
