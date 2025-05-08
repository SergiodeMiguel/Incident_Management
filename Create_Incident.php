<?php
// Connect to the database
$host = 'localhost';
$db = 'incident_managementdb';
$user = 'root'; // Cambia si usas otro usuario
$pass = '';     // Cambia si tu usuario tiene contraseña

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$users = $conn->query("SELECT id, name FROM users");

// Fetch categories
$categories = $conn->query("SELECT id, name FROM categories");

// Fetch departments
$departments = $conn->query("SELECT id, name FROM departments");

// Fetch existing incidents for display
$incidents = $conn->query(
  "SELECT i.id, i.title, i.description, i.status, i.creation_date,
  u.name as user_name, c.name as category_name, d.name as department_name
  FROM incidents i JOIN users u ON i.user_id = u.id
  JOIN categories c ON i.category_id = c.id
  JOIN departments d ON i.department_id = d.id
  ORDER BY i.creation_date DESC");

// Check for success message
$success_message = isset($_GET['success']) ? "Incident successfully added!" : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Incident Management</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <!-- Header with navigation -->
  <header class="header">
    <nav class="nav">
      <a href="indice2.php" class="nav-logo">Incident Manager</a>
      <div class="nav-links">
        <a href="indice2.php" class="nav-link active">Dashboard</a>
        <a href="#" class="nav-link">Reports</a>
        <a href="#" class="nav-link">Settings</a>
      </div>
    </nav>
  </header>

  <div class="container">
    <?php if($success_message): ?>
      <div class="notification notification-success fade-in">
        <?php echo $success_message; ?>
      </div>
    <?php endif; ?>

    <h1>Incident Management System</h1>

    <div class="grid grid-2">
      <!-- Incident Form -->
      <div class="card">
        <h2>Create New Incident</h2>
        <form id="incident-form" method="POST" action="submit_incident.php">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required />
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <span class="form-hint">Provide detailed information about the incident</span>
          </div>

          <div class="grid grid-2">
            <!-- Users dropdown from DB -->
            <div class="form-group">
              <label for="user">Reported By</label>
              <select id="user" name="user" required>
                <option value="">Select User</option>
                <?php while($row = $users->fetch_assoc()): ?>
                  <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <!-- Categories dropdown from DB -->
            <div class="form-group">
              <label for="category">Category</label>
              <select id="category" name="category" required>
                <option value="">Select Category</option>
                <?php while($row = $categories->fetch_assoc()): ?>
                  <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>

          <div class="grid grid-2">
            <!-- Departments dropdown from DB -->
            <div class="form-group">
              <label for="department">Department</label>
              <select id="department" name="department" required>
                <option value="">Select Department</option>
                <?php while($row = $departments->fetch_assoc()): ?>
                  <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <!-- Static statuses for now -->
            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Resolved">Resolved</option>
              </select>
            </div>
          </div>

          <div class="flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-block">Add Incident</button>
            <button type="reset" class="btn btn-outline">Reset</button>
          </div>
        </form>
      </div>

      <!-- Recent Incidents -->
      <div>
        <div class="card">
          <h2>Recent Incidents</h2>
          
          <?php if($incidents && $incidents->num_rows > 0): ?>
            <div class="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($incident = $incidents->fetch_assoc()): ?>
                    <tr>
                      <td>
                        <?= htmlspecialchars($incident['title']) ?>
                        <div class="text-muted"><?= htmlspecialchars($incident['user_name']) ?></div>
                      </td>
                      <td>
                        <?php 
                          $statusClass = '';
                          switch($incident['status']) {
                            case 'Open':
                              $statusClass = 'status-open';
                              break;
                            case 'In Progress':
                              $statusClass = 'status-in-progress';
                              break;
                            case 'Resolved':
                              $statusClass = 'status-resolved';
                              break;
                            default:
                              $statusClass = 'status-open';
                          }
                        ?>
                        <span class="status <?= $statusClass ?>"><?= htmlspecialchars($incident['status']) ?></span>
                      </td>
                      <td><?= htmlspecialchars($incident['department_name']) ?></td>
                      <td>
                        <div class="actions">
                          <a href="view_incident.php?id=<?= $incident['id'] ?>" class="btn btn-sm btn-outline">View</a>
                          <a href="edit_incident.php?id=<?= $incident['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            <div class="mt-3 text-center">
              <a href="all_incidents.php" class="btn btn-outline">View All Incidents</a>
            </div>
          <?php else: ?>
            <p class="text-muted text-center">No incidents found. Create your first incident using the form.</p>
          <?php endif; ?>
        </div>

        <!-- Quick Stats Card -->
        <div class="card mt-4">
          <h2>Quick Stats</h2>
          <div class="grid grid-3 mt-3">
            <div class="incident-card text-center">
              <h3 class="incident-card-title">Open</h3>
              <div class="text-primary" style="font-size: 2rem; font-weight: bold;">
                <?php
                  $openCount = $conn->query("SELECT COUNT(*) as count FROM incidents WHERE status = 'Open'")->fetch_assoc();
                  echo $openCount['count'];
                ?>
              </div>
            </div>
            <div class="incident-card text-center">
              <h3 class="incident-card-title">In Progress</h3>
              <div class="text-warning" style="font-size: 2rem; font-weight: bold;">
                <?php
                  $inProgressCount = $conn->query("SELECT COUNT(*) as count FROM incidents WHERE status = 'In Progress'")->fetch_assoc();
                  echo $inProgressCount['count'];
                ?>
              </div>
            </div>
            <div class="incident-card text-center">
              <h3 class="incident-card-title">Resolved</h3>
              <div class="text-success" style="font-size: 2rem; font-weight: bold;">
                <?php
                  $resolvedCount = $conn->query("SELECT COUNT(*) as count FROM incidents WHERE status = 'Resolved'")->fetch_assoc();
                  echo $resolvedCount['count'];
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Auto-hide notification after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const notification = document.querySelector('.notification');
      if (notification) {
        setTimeout(function() {
          notification.style.opacity = '0';
          notification.style.transition = 'opacity 0.5s';
          setTimeout(function() {
            notification.remove();
          }, 500);
        }, 5000);
      }
    });
  </script>
</body>
</html>
