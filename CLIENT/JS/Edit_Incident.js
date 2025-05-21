/* JAVASCRIPT FOR INCIDENT EDIT FORM
   This script dynamically fills in dropdowns and fields in the edit form using data fetched from the server,
   handles form submission via POST to update the incident,
   and redirects the user to the incident list page upon successful update.
*/

// Access protection: redirect to Login if not logged in
if (localStorage.getItem("isLoggedIn") !== "true") {
  window.location.href = "Login.html";
}


// Wait until the entire HTML document is fully loaded and parsed
document.addEventListener("DOMContentLoaded", async () => {

  // Get a reference to the form element using its ID
  const form = document.getElementById("edit-incident-form");

  // Get query parameters from the URL (e.g., ?id=5)
  const urlParams = new URLSearchParams(window.location.search);

  // Extract the value of the 'id' parameter from the URL (incident ID to be edited)
  const incidentId = urlParams.get("id");

  // If no incident ID is present in the URL, alert the user and redirect to the incident list
  if (!incidentId) {
    alert("Incident ID not found.");
    window.location.href = "All_Incidents.html";
    return; // Exit early
  }

  try {
    // Send parallel GET requests to fetch categories, departments, users, and the specific incident details
    const [categories, departments, users, incident] = await Promise.all([
      fetch("../../SERVER/API/Categories/Categories_API.php").then(res => res.json()),     // Categories list
      fetch("../../SERVER/API/Departments/Departments_API.php").then(res => res.json()),   // Departments list
      fetch("../../SERVER/API/Users/Users_API.php").then(res => res.json()),               // Users list
      fetch(`../../SERVER/API/Incidents/Get_Incident_By_ID.php?id=${incidentId}`).then(res => res.json()) // Incident data by ID
    ]);

    // If the server responds with a failure (incident not found), throw an error to be caught below
    if (!incident.success) throw new Error("Incident not found");

    // Populate the dropdowns and set the selected value based on the existing incident data
    fillSelect("category", categories.data, incident.data.category_id);                // Set category select
    fillSelect("department", departments.data, incident.data.department_id);           // Set department select
    fillSelect("user", users.data, incident.data.user_id);                             // ID 'user' from HTML used for 'Reported by'
    fillSelect("assigned_user", users.data, incident.data.assigned_user_id);           // ID 'assigned_user' from HTML used for 'Assigned to'

    // Populate the text fields (title, description, status) with the existing data
    fillForm(incident.data);

  } catch (error) {
    // If any fetch request fails, log the error and notify the user
    console.error("Error loading data:", error);
    alert("Error loading incident details.");
  }

  // Add an event listener to handle form submission
  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the default form submission behavior

    // Create a JavaScript object with all form data to send to the server
    const formData = {
      id: incidentId,                                           // ID of the incident being updated
      title: form.title.value.trim(),                           // Title of the incident
      description: form.description.value.trim(),               // Description field
      status: form.status.value,                                // Status of the incident (e.g., Open, Closed)
      category_id: parseInt(form.category.value),               // Selected category ID
      department_id: parseInt(form.department.value),           // Selected department ID
      user_id: parseInt(form.user.value),                       // User who reported the incident (ID 'user' matches <select>)
      assigned_user_id: parseInt(form.assigned_user.value) || null // Assigned user (null if not selected, ID 'assigned_user')
    };

    try {
      // Send the form data to the server using a POST request in JSON format
      const res = await fetch("../../SERVER/API/Incidents/Update_Incident.php", {
        method: "POST",                             // Use POST for sending data
        headers: { "Content-Type": "application/json" }, // Set content type to JSON
        body: JSON.stringify(formData)              // Convert JavaScript object to JSON string
      });

      const data = await res.json(); // Parse the JSON response from the server

      // If the server response indicates failure, throw an error
      if (!data.success) throw new Error(data.message);

      // Notify the user of success and redirect to the list of all incidents
      alert("Incident updated successfully.");
      window.location.href = "All_Incidents.html";

    } catch (error) {
      // Handle any errors that occur during the update request
      console.error("Error updating incident:", error);
      alert("Failed to update incident.");
    }
  });

  /**
   * Populates a <select> dropdown with options and selects the current value
   * @param {string} selectId - ID of the select element in the DOM
   * @param {Array} options - Array of objects containing {id, name}
   * @param {number} selectedId - ID of the currently selected option
   */
  function fillSelect(selectId, options, selectedId) {
    const select = document.getElementById(selectId); // Get the <select> element by ID
    // Replace its inner HTML with <option> elements created from the fetched data
    select.innerHTML = options.map(opt => {
      const value = opt.id;
      const label = opt.name;
      const selected = value == selectedId ? "selected" : ""; // Mark as selected if matches
      return `<option value="${value}" ${selected}>${label}</option>`;
    }).join(""); // Join all option strings into a single string and set as innerHTML
  }

  /**
   * Populates text input fields in the form with the current incident data
   * @param {Object} data - Incident data returned from the server
   */
  function fillForm(data) {
    form.title.value = data.title;             // Set the title input
    form.description.value = data.description; // Set the description input
    form.status.value = data.status;           // Set the status select
  }
});

// Logout function: clears session and redirects to Login
function logout() {
  localStorage.clear();
  window.location.href = "../HTML/Login.html";
}
