/* HOME.JS – Script to handle dynamic content on the homepage
   This script handles displaying dynamic content on the homepage,
   such as personalized messages, statistics, and a list of recent incidents.
*/

// Retrieve user info from localStorage
const currentUser = JSON.parse(localStorage.getItem("user"));

// Map role_id to a readable role name
// This allows later checks like: if (currentUser.role === "admin")
  if (!currentUser || !currentUser.role) {
    // Por seguridad, limpia y redirige al login
    localStorage.clear();
    window.location.href = "Login.html";
  }
// Waits until the DOM content is fully loaded
document.addEventListener("DOMContentLoaded", () => {

  // Access protection: redirect to Login if not logged in
  console.log("isLoggedIn:", localStorage.getItem("isLoggedIn")); // DEBUG
  console.log("user:", localStorage.getItem("user")); // DEBUG

  if (localStorage.getItem("isLoggedIn") !== "true" || !currentUser) {
    // New comment: If user is not logged in or user data is missing, clear storage and redirect to login
    localStorage.clear();
    window.location.href = "Login.html";
    return; // Prevent further execution
  }

  const welcomeMsg = document.getElementById("welcome-message");
  // New comment: Display welcome message with user name dynamically
  welcomeMsg.textContent = `Hello, ${currentUser.name}`;

  /* Function calls like loadRecentIncidents() work here even though the function is defined later.
     This is possible because we use a classic function declaration (function loadRecentIncidents() {...}),
     which is hoisted entirely to the top of the scope during compilation.*/
  loadStats();
  loadRecentIncidents();

  // Show admin-only columns if user is admin
  console.log("Usuario actual:", currentUser);
  if (currentUser.role === "admin") {
    document.querySelectorAll(".admin-only").forEach(el => {
      el.classList.add("admin-visible");
    }); 
  } else {
    // Ensure admin-only elements remain hidden if not admin
    document.querySelectorAll(".admin-only").forEach(el => {
      el.classList.remove("admin-visible");
    });
  }
});

// Function to load and display open/closed incident stats
function loadStats() {
  fetch("../../SERVER/API/Incidents/Get_Incident_Stats.php")
    .then(response => response.json())
    .then(data => {
      document.getElementById("open-count").textContent = data.open;
      document.getElementById("closed-count").textContent = data.closed;
    })
    .catch(error => {
      console.error("Error loading statistics:", error);
    });
}

// Function to load and display recent incidents in the table
function loadRecentIncidents() {
  fetch("../../SERVER/API/Incidents/Get_Latest_Incidents.php")
    .then(response => response.json())
    .then(data => {
      const tableBody = document.getElementById("incident-list");
      tableBody.innerHTML = ""; // Clear previous data

      data.forEach(incident => {
        const row = document.createElement("tr");

        // Admin-only ID column
        if (currentUser.role === "admin") {
          const idCell = document.createElement("td");
          idCell.textContent = incident.id;
          row.appendChild(idCell);
        }

        // Define the fields to display for all users
        const fields = [
          incident.title || "—", // Show dash if missing
          incident.status,
          incident.description || "—",
          incident.category || "—",
          incident.department || "—",
          incident.reported_by || "—",
          incident.assigned_to || "—",
          (new Date(incident.creation_date)).toLocaleDateString("en-GB"),
        ];

        // New comment: Append each field as a table cell
        fields.forEach(text => {
          const cell = document.createElement("td");
          cell.textContent = text;
          row.appendChild(cell);
        });

        // Actions column: Edit and Delete buttons
        const actionsCell = document.createElement("td");

        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.className = "edit-btn";
        editBtn.onclick = () => {
          // New comment: Redirect to edit incident page with incident id as parameter
          window.location.href = `Edit_Incident.html?id=${incident.id}`;
        };
        actionsCell.appendChild(editBtn);

        const deleteBtn = document.createElement("button");
        deleteBtn.textContent = "Delete";
        deleteBtn.className = "delete-btn";
        deleteBtn.onclick = () => confirmDelete(incident.id);
        actionsCell.appendChild(deleteBtn);

        row.appendChild(actionsCell);
        tableBody.appendChild(row);
      });
    })
    .catch(error => {
      console.error("Error loading recent incidents:", error);
    });
}

// Function to confirm and delete an incident
function confirmDelete(id) {
  if (confirm("Are you sure you want to delete this incident?")) {
    fetch("../../SERVER/API/Incidents/Delete_Incident.php", {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json", // Set content-type header
      },
      body: JSON.stringify({ id: id }) // Send id in request body
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Incident deleted successfully.");
          loadRecentIncidents();  // Reload incidents after deletion
        } else {
          alert("Error deleting incident: " + data.message);
        }
      })
      .catch(error => {
        console.error("Delete request failed:", error);
      });
  }
}

// Logout function: clears session and redirects to Login
function logout() {
  // Clear local session data
  localStorage.removeItem('user');
  localStorage.removeItem('isLoggedIn');

  // Redirect directly to login.html without calling Keycloak logout
  window.location.href = 'Login.html';
}