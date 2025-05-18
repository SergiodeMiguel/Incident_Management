// Wait until the entire HTML document is fully loaded and parsed
document.addEventListener('DOMContentLoaded', () => {

  // Defines an object that associates the names of the dropdowns with their respective IDs of the <select> elements.
  const selects = {
    // The keys here are the names used in the API and the values are the IDs of the <select> elements in the HTML
    // user: key in the object, 'user': value in the object (ID of the select element) 
    user: 'user', // Refers to the "Reported By" select dropdown
    assigned_user: 'assigned_user', // Refers to the "Assigned To" select dropdown
    category: 'category', // Refers to the "Category" select dropdown
    department: 'department' // Refers to the "Department" select dropdown
  };

  // Define the corresponding API endpoints from which to fetch the data for each dropdown
  const endpoints = {
    user: '../../SERVER/API/Users/Users.php', // Endpoint that returns user data
    assigned_user: '../../SERVER/API/Users/Users.php', // Same endpoint for assigned users
    category: '../../SERVER/API/Categories/Categories.php', // Endpoint that returns category data
    department: '../../SERVER/API/Departments/Departments.php' // Endpoint that returns department data
  };

  /* Loop through each element of the “selects” object using deconstruction to access the element's key and ID
    This allows to dynamically create the dropdowns based on the keys and IDs defined above
    Object.entries() returns an array with the [key, value] pairs of the enumerable properties of an object.*/
  for (const [key, selectId] of Object.entries(selects)) {
    // Perform a GET request to the matching endpoint
    
    fetch(endpoints[key]) // Use the key to get the correct endpoint from the endpoints object
      .then(res => res.json()) // Convert the server response from JSON string to JavaScript object
      .then(data => {
        // Select the corresponding <select> element in the DOM using its ID
        const select = document.getElementById(selectId);

        // Loop through the array of data returned by the API
        data.forEach(item => {
          // Create a new <option> HTML element for each item
          const option = document.createElement('option');
          option.value = item.id;       // Set the 'value' attribute to the item's ID (used when submitting form)
          option.textContent = item.name; // Set the visible text to the item's name
          select.appendChild(option);   // Append the <option> to the corresponding <select> element
        });
      })
      .catch(err => console.error(`Error fetching ${key}:`, err)); // Print error to console if request fails
  }

  // Get a reference to the <form> element using its ID and add an event listener to listen for the 'submit' event
  document.getElementById('incident-form').addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the default form submission behavior (which reloads the page)

    // Create a new FormData object to gather all form inputs
    // 'e.target' refers to the form element that triggered the submit event
    // FormData automatically collects all <input>, <textarea>, and <select> fields within the form
    const formData = new FormData(e.target);

    // Send the collected form data to the server using a POST request
    fetch('../../SERVER/API/Incidents/Create_Incident_Insert.php', {
      method: 'POST',     // Specify HTTP method as POST for data submission
      body: formData      // Attach the FormData object as the request body
    })
      .then(res => res.json()) // Parse the JSON response from the server
      .then(result => {
        // Check if the server responded with a success flag
        if (result.success) {
          // Redirect the user to the success confirmation page upon successful incident creation.
          // Change the URL below to match your project structure or desired confirmation page.
          window.location.href = '../HTML/Success_Incident.html'; // Change this line to redirect elsewhere if needed
          
          // Optionally, if you want to keep the alert and reset the form instead of redirect, change the line above to:
          // alert('Incident created successfully!');
          // e.target.reset();
        } 
        else {
          // If the server sends back an error, show it to the user
          alert('Error: ' + result.message);
        }
      })
      .catch(err => alert('Error: ' + err)); // Show any network or server error that occurred
  });
});
