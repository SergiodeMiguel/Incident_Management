/* ALL_INCIDENTS.JS – Script to load and display all incidents
   This script fetches all incidents from the backend and renders them into a dynamic table.
   It also supports admin-only columns and allows deletion of incidents via a confirmation dialog.
*/

  // Retrieve user info from localStorage
  const currentUser = JSON.parse(localStorage.getItem("user"));
  
  // Map role_id to a readable role name
  // This allows later checks like: if (currentUser.role === "admin")
  if (currentUser.role_id === 1) {
    currentUser.role = "admin";
  } else {
    currentUser.role = "user"; // or another role name if needed
  }

// Waits until the DOM content is fully loaded
document.addEventListener("DOMContentLoaded", () => {

  // Access protection: redirect to Login if not logged in

  console.log("isLoggedIn:", localStorage.getItem("isLoggedIn")); // DEBUG
  console.log("user:", localStorage.getItem("user")); // DEBUG

  if (localStorage.getItem("isLoggedIn") !== "true") {
    window.location.href = "Login.html";
  }

  // Redirect to login page if no user is logged in
  if (!currentUser) {
    // If somehow user is missing, treat as not logged in
    localStorage.clear();
    window.location.href = "Login.html";
  }
  
  // Show admin-only columns if user is admin
  if (currentUser.role === "admin") {
    document.querySelectorAll(".admin-only").forEach(el => {
      el.classList.add("admin-visible"); // Muestra columnas admin
    });
  } else {
    document.querySelectorAll(".admin-only").forEach(el => {
      el.classList.remove("admin-visible"); // Oculta columnas admin
    });
  }


  // Load all incidents
  loadAllIncidents();
});

// Function to load and display all incidents in the table
function loadAllIncidents() {
  fetch("../../SERVER/API/Incidents/All_Incidents.php") // Fetch incidents from API
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
      return response.json(); // Parse the response as JSON
    })
    .then(responseData => {
      // Check if the response is successful
      if (!responseData.success) {
        console.error("Server error:", responseData.message, responseData.error);
        alert("Error loading incidents.");
        return;
      }

      const incidents = responseData.data;
      const tableBody = document.getElementById("all-incident-list");
      tableBody.innerHTML = ""; // Clear previous data

      // If no incidents found, display message
      if (incidents.length === 0) {
        const noDataRow = document.createElement("tr");
        const noDataCell = document.createElement("td");
        noDataCell.colSpan = 10;
        noDataCell.textContent = "No incidents found."; // No incidents message
        noDataRow.appendChild(noDataCell);
        tableBody.appendChild(noDataRow);
        return;
      }

      // Loop through incidents and create rows in the table
      incidents.forEach(incident => {
        const row = document.createElement("tr");

        // Admin-only column: Display ID
        if (currentUser.role.toLowerCase() === "admin") {
          const idCell = document.createElement("td");
          idCell.textContent = incident.id;
          row.appendChild(idCell);
        }

        // Title column
        const titleCell = document.createElement("td");
        titleCell.textContent = incident.title || "—"; // Display "—" if title is not available
        row.appendChild(titleCell);

        // Status column (without color circle)
        const statusCell = document.createElement("td");
        statusCell.textContent = incident.status; // Just the status text, no circle
        row.appendChild(statusCell);

        // Description column
        const descriptionCell = document.createElement("td");
        descriptionCell.textContent = incident.description || "—"; // Display "—" if description is not available
        row.appendChild(descriptionCell);

        // Category column
        const categoryCell = document.createElement("td");
        categoryCell.textContent = incident.category || "—"; // Display "—" if category is not available
        row.appendChild(categoryCell);

        // Department column
        const departmentCell = document.createElement("td");
        departmentCell.textContent = incident.department || "—"; // Display "—" if department is not available
        row.appendChild(departmentCell);

        // Reported By column
        const reportedByCell = document.createElement("td");
        reportedByCell.textContent = incident.reported_by || "—"; // Display "—" if reported_by is not available
        row.appendChild(reportedByCell);

        // Assigned To column
        const assignedToCell = document.createElement("td");
        assignedToCell.textContent = incident.assigned_to || "—"; // Display "—" if assigned_to is not available
        row.appendChild(assignedToCell);

        // Date column (formatted as DD/MM/YYYY)
        const dateCell = document.createElement("td");
        const rawDate = new Date(incident.created_at);
        dateCell.textContent = isNaN(rawDate.getTime()) ? "—" : rawDate.toLocaleDateString("en-GB");
        row.appendChild(dateCell);

        // Actions column (Edit and Delete buttons)
        const actionsCell = document.createElement("td");

        // Edit Button
        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.className = "edit-btn action-btn";
        editBtn.onclick = () => {
          window.location.href = `Edit_Incident.html?id=${incident.id}`; // Redirect to Edit page
        };
        actionsCell.appendChild(editBtn);

        // Delete Button
        const deleteBtn = document.createElement("button");
        deleteBtn.textContent = "Delete";
        deleteBtn.className = "delete-btn action-btn";
        deleteBtn.onclick = () => confirmDelete(incident.id); // Trigger delete confirmation
        actionsCell.appendChild(deleteBtn);

        row.appendChild(actionsCell); // Append actions column to row
        tableBody.appendChild(row); // Append row to table body
      });
    })
    .catch(error => {
      console.error("Fetch error:", error); // Log error if something goes wrong
      alert("Failed to load incidents. Check console for details.");
    });
}

// Function to confirm deletion and perform the actual deletion
function confirmDelete(id) {
  if (confirm("Are you sure you want to delete this incident?")) {
    console.log("Attempting to delete incident with ID:", id); // Debugging line to see the ID being sent

    // Send DELETE request to API with incident ID in the body
    fetch(`../../SERVER/API/Incidents/Delete_Incident.php`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json", // Set request content type to JSON
      },
      body: JSON.stringify({ id: id }) // Send the ID in the body of the request
    })
      .then(response => response.json()) // Parse the JSON response
      .then(data => {
        console.log("Delete response:", data); // Debugging the response from the server
        if (data.success) {
          alert("Incident deleted successfully.");
          loadAllIncidents(); // Reload incidents after deletion
        } else {
          alert("Error deleting incident: " + data.message); // Show error message if deletion fails
        }
      })
      .catch(error => {
        console.error("Delete error:", error); // Log error if something goes wrong
        alert("Error deleting incident. Check console for details.");
      });
  }
}

// Logout function: clears session and redirects to Login
function logout() {
  localStorage.clear();
  window.location.href = "../HTML/Login.html";
}