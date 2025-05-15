document.addEventListener('DOMContentLoaded', () => {
  // Mapping of form fields to select IDs
  const selects = {
    user: 'user', // Updated to match the HTML ID
    assigned_user: 'assigned_user', // Updated to match the HTML ID
    category: 'category', // Updated to match the HTML ID
    department: 'department' // Updated to match the HTML ID
  };

  // Mapping of API endpoints for each dropdown
  const endpoints = {
    user: '../../SERVER/API/users/Users.php', // Path to endpoint for users
    assigned_user: '../../SERVER/API/users/Users.php', // Path to endpoint for users
    category: '../../SERVER/API/categories/Categories.php', // Path to endpoint for categories
    department: '../../SERVER/API/departments/Departments.php' // Path to endpoint for departments
  };

  // Loop through each select field and populate it from API
  for (const [key, selectId] of Object.entries(selects)) {
    fetch(endpoints[key])
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById(selectId);
        // Append each item as <option>
        data.forEach(item => {
          const option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.name;
          select.appendChild(option);
        });
      })
      .catch(err => console.error(`Error fetching ${key}:`, err)); // Added error handling
  }

  // Handle form submission
  document.getElementById('incident-form').addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent default form submit

    const formData = new FormData(e.target); // Collect form data

    // Send data to the API using POST
    fetch('../../SERVER/API/incidents/create.php', { // Path to endpoint for users
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(result => {
        // Display success or error message
        if (result.success) {
          alert('Incident created successfully!');
          e.target.reset(); // Reset form on success
        } else {
          alert('Error: ' + result.message);
        }
      })
      .catch(err => alert('Error: ' + err));
  });
});