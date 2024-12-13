<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL password
$dbname = "login_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Number of records per page
$records_per_page = 10;

// Get current page number from query string, default to 1 if not set
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record for the query
$start_from = ($page_number - 1) * $records_per_page;

// Fetch total number of records
$total_records_sql = "SELECT COUNT(id) AS total FROM user";
$total_result = $conn->query($total_records_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// Calculate total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Fetch records for the current page
$sql = "SELECT id, username, role FROM user LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>

<html>
<head>

    <title>Manage Student Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 0;
        padding: 0;
    }
    h1 {
        margin: 20px 0;
    }
    table {
        margin: 20px auto;
        border-collapse: collapse;
        width: 100%;
        max-width: 800px; /* Adjust the max-width as needed */
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 10px;
    }
    th {
        background-color: #f4f4f4;
    }
    .pagination {
        margin: 20px 0;
    }
    .pagination a {
        text-decoration: none;
        color: #007bff;
        padding: 10px;
    }
    .pagination a:hover {
        text-decoration: underline;
    }
    form {
        display: inline;
    }
    .add-user {
        text-align: center;
        margin-bottom: 20px;
    }
    .add-user a {
        background: #007bff;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }
    .add-user a:hover {
        background: #0056b3;
    }
    .button-icon {
        background: red;
        border: none;
        color: white;
        padding: 5px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 16px; /* Adjust icon size */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .add-user a {
            font-size: 14px;
            padding: 8px 16px;
        }
        table {
            width: 100%;
            font-size: 14px;
        }
        th, td {
            padding: 8px;
        }
        .pagination a {
            padding: 8px;
            font-size: 14px;
        }
        .button-icon {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        h1 {
            font-size: 24px;
        }
        .add-user a {
            font-size: 12px;
            padding: 6px 12px;
        }
        table {
            font-size: 12px;
        }
        th, td {
            padding: 6px;
        }
        .pagination a {
            padding: 6px;
            font-size: 12px;
        }
        .button-icon {
            font-size: 12px;
        }
    }
</style>

</head>
<body>

<h1>Manage Student Details</h1>

<!-- Add New User button -->
<div class="add-user">
    <a href="./Rec_cls_signup.php">Add New User</a>
</div>

<!-- Show all users -->
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result) {
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                    <form method="post" action="ManageStudent.php" style="display: inline;">
    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>" />
    <button type="submit" name="delete" style="background: red; border: none; color: white; padding: 5px; border-radius: 3px; cursor: pointer;">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>

                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='4'>No users found</td></tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Error fetching users: " . $conn->error . "</td></tr>";
    }
    ?>

</table>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page_number > 1): ?>
        <a href="ManageStudent.php?page=<?php echo $page_number - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php if ($page_number < $total_pages): ?>
        <a href="ManageStudent.php?page=<?php echo $page_number + 1; ?>">Next</a>
    <?php endif; ?>
</div>

<?php
// Handle user deletion
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM user WHERE id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully";
        header("Refresh:0"); // Refresh the page after deletion
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

</body>
</html>
