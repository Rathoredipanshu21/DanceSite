<?php
session_start();

require 'db.php'; // Include the database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the query to check if username already exists
    $check_stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<div class='message error'>Username already exists. Please choose a different username.</div>";
    } else {
        // Prepare and execute the query to insert new user
        $insert_stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
        $insert_stmt->bind_param("ss", $username, $hashed_password);

        if ($insert_stmt->execute()) {
            echo "<div class='message success'>Registration successful!</div>";
        } else {
            echo "<div class='message error'>Error: " . $insert_stmt->error . "</div>";
        }

        // Close the prepared statement
        $insert_stmt->close();
    }

    // Close the prepared statement for checking username existence
    $check_stmt->close();
}

// Close the database connection (optional for this page)
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h1 {
            margin-top: 0;
            color: #4CAF50;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>
