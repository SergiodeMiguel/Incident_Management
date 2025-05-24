<?php
/*
   keycloak_login.php - This script handles user authentication via Keycloak using OpenID Connect.
   It initiates the login process, retrieves the access token, decodes it to extract
   user roles, and sets session data for frontend access using localStorage.
   It determines whether the user has the "admin" role and redirects to the frontend.
   Used in conjunction with the Keycloak Identity Provider and the Jumbojett OpenID Connect PHP library.
 */

// Enable full error reporting for development/debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the PHP session to manage user data if needed later
session_start();

// Load all Composer dependencies including Jumbojett OpenID client
require __DIR__ . '/../../../vendor/autoload.php';

// Import the OpenIDConnectClient class into the current namespace
use Jumbojett\OpenIDConnectClient;

// Instantiate the OpenIDConnectClient with Keycloak parameters
$oidc = new OpenIDConnectClient(
    'http://localhost:8080/realms/Incident-System', // Keycloak realm URL
    'php-app',                                      // Client ID registered in Keycloak
    ''                                              // Client secret (empty if using public client)
);

// Define the redirect URL for Keycloak to return the user after authentication
$oidc->setRedirectURL('http://localhost/PROYECT/SERVER/API/Login/keycloak_login.php');

try {
    // Force the Keycloak login screen to appear even if the user has a session
    $oidc->addAuthParam(['prompt' => 'login']);

    // Begin the authentication process (redirects to Keycloak and back)
    $oidc->authenticate();

    // After successful authentication, request user information from the token
    $userInfo = $oidc->requestUserInfo();

    // Retrieve the access token (JWT format) which contains user roles and other claims
    $idToken = $oidc->getAccessToken();  // <- we need access_token to access realm roles

    // Split the JWT token into its parts: header.payload.signature
    $jwtParts = explode('.', $idToken);

    // Decode the payload (base64url), which contains the claims including roles
    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $jwtParts[1])), true);

    // Extract the roles array from the "realm_access" section of the payload
    $roles = $payload['realm_access']['roles'] ?? [];

    // Check if the user has the 'admin' role
    $isAdmin = in_array('admin', $roles);

    // Create a user object with username and role for frontend use
    $user = [
        'name' => $userInfo->preferred_username ?? 'User',   // fallback in case username is missing
        'role' => $isAdmin ? 'admin' : 'user'                // determine role for client UI behavior
    ];

    // Output HTML that sets localStorage values and redirects to the home page
    echo "<!DOCTYPE html><html><head><title>Redirigiendo...</title></head><body>";
    echo "<script>
        localStorage.setItem('user', JSON.stringify(" . json_encode($user) . "));
        localStorage.setItem('isLoggedIn', 'true');
        window.location.href = '/PROYECT/CLIENT/HTML/Home.html'; // redirect to frontend home
    </script>";
    echo "</body></html>";

} catch (Exception $e) {
    // Handle and display any error during the authentication process
    echo 'Authentication error: ' . $e->getMessage();
}
