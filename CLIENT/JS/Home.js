/* HOME.JS – Script to handle dynamic content on the homepage
   This script handles displaying dynamic content on the homepage,
   such as personalized messages, statistics, and a list of recent incidents.
*/

// Simulated data for the current user (will be replaced with real data once login is implemented)
const currentUser = {
  id: 1,                          // User ID
  name: "Sergio",                 // User name
  role: "Admin"                   // User role ("user" or "admin")
};

// Waits until the DOM content is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  const welcomeMsg = document.getElementById("welcome-message");
  welcomeMsg.textContent = `Hello, ${currentUser.name}`;

  loadStats();
  loadRecentIncidents();

  // Show admin-only columns if user is admin
  if (currentUser.role === "admin") {
    document.querySelectorAll(".admin-only").forEach(el => {
      el.style.display = "table-cell";
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

        // Title
        const titleCell = document.createElement("td");
        titleCell.textContent = incident.title;
        row.appendChild(titleCell);

        // Status
        const statusCell = document.createElement("td");
        statusCell.textContent = incident.status;
        row.appendChild(statusCell);

        // Category
        const categoryCell = document.createElement("td");
        categoryCell.textContent = incident.category || "—";
        row.appendChild(categoryCell);

        // Department
        const departmentCell = document.createElement("td");
        departmentCell.textContent = incident.department || "—";
        row.appendChild(departmentCell);

        // Date
        const dateCell = document.createElement("td");
        dateCell.textContent = incident.date || "—";
        row.appendChild(dateCell);

        // Actions
        const actionsCell = document.createElement("td");

        // Edit Button
        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.className = "edit-btn";
        editBtn.onclick = () => {
          window.location.href = `Edit_Incident.html?id=${incident.id}`;
        };
        actionsCell.appendChild(editBtn);

        // Delete Button
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
    fetch(`../../SERVER/API/Incidents/Delete_Incident.php?id=${id}`, {
      method: "DELETE"
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Incident deleted successfully.");
          loadRecentIncidents();
        } else {
          alert("Error deleting incident.");
        }
      })
      .catch(error => {
        console.error("Delete request failed:", error);
      });
  }
}
