<?php
session_start();

require 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);

    // Prepare and execute the query
    $query = "SELECT * FROM user WHERE username=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Fetch the hashed password from the database
        $hashed_password = $row['password'];

        // Verify the entered password with the hashed password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['username'] = $username;
            header('Location: ./Gallery.php'); // Redirect to the desired page
            exit();
        } else {
            // Login failed
            echo "Incorrect username or password.";
        }
    } else {
        // Username not found
        echo "Incorrect username or password.";
    }

    // Close the prepared statements and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Modal</title>
    <style>
        /* Basic styling for the body */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevents scrolling when modal is open */
        }

        /* Style for the content that will be blurred */
        .content {
            position: relative;
            width: 100vw;
            height: 100vh;
           
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent image repetition */
        }

        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Style the form elements */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Blur effect for the background */
        .blurred-background {
            filter: blur(4px);
            pointer-events: none; /* Prevent interaction with blurred background */
        }
    </style>
</head>
<body>
    <!-- Content that will be blurred -->
    <div class="content blurred-background">
        <!-- Your existing content, e.g., gallery content, goes here -->
    </div>

    <!-- The Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h1>Login</h1>
            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br><br>

                <input type="submit" value="Login">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("loginModal");

        // Show the modal when the page loads
        window.onload = function() {
            modal.style.display = "block";
            document.querySelector('.content').classList.add('blurred-background');
        };

        // No close button functionality
        // No close event on clicking outside of the modal
    </script>
</body>
</html>
