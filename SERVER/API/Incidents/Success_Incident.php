<?php
// Redirect back to the form if accessed directly without the ?success=1 parameter
if (!isset($_GET['success']) || $_GET['success'] != 1) {
    header("Location: create_incident.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incident Created</title>

    <!-- Automatically redirect to the form page after 3 seconds -->
    <meta http-equiv="refresh" content="3;URL='create_incident.php'">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
        }

        .emoji {
            font-size: 50px;
            margin-bottom: 15px;
        }

        h2 {
            color: #28a745;
            margin-bottom: 10px;
        }

        p {
            color: #333;
            font-size: 15px;
        }

        .redirect {
            font-size: 13px;
            color: #777;
            margin-top: 15px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="emoji">✔️</div>
        <h2>Incident successfully created!</h2>
        <p>Your incident has been registered in the system.</p>
        <div class="redirect">You will be redirected to the form in 3 seconds...</div>
    </div>
</body>
</html>
