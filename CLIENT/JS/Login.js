/*  OWN LOGIN JS. IN CASE OF NOT USING KEYCLOAK, THIS FILE IS USED.
  JAVASCRIPT FOR LOGIN FORM
  This script handles the login form submission, sends the user and password
  as a POST request to the server, and handles success or error responses.


document.getElementById('loginForm').addEventListener('submit', async function (e) {
  e.preventDefault(); // Prevent default form submission behavior

  const user = document.getElementById('user').value;
  const password = document.getElementById('password').value;

  try {
    // Send a POST request to the login API with JSON-encoded credentials
    const response = await fetch('../../SERVER/API/Login/Login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json' // Specify that we're sending JSON
      },
      body: JSON.stringify({ user, password }) // Convert JS object to JSON
    });

    const result = await response.json(); // Parse the JSON response

    if (result.success) {
      // Save user data to localStorage for session simulation
      localStorage.setItem('user', JSON.stringify(result.user));
      localStorage.setItem('isLoggedIn', "true");

      
      console.log("Login OK: ", localStorage.getItem("isLoggedIn")); // DEBUG
      // If login is successful, redirect to homepage or dashboard
      window.location.href = '../HTML/Home.html';
    } else {
      // If login failed, show the error message on the page
      document.getElementById('error-message').textContent = result.message;
    }

  } catch (error) {
    // Handle fetch/network errors
    console.error('Login request failed:', error);
    document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
  }
}); */