<?php
// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uploadSuccess = false; // To track if the upload was successful
$message = ""; // To store the message that will be shown in the alert

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Upload functionality
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $caption = $_POST['caption'];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = true;

        if (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
            $uploadOk = false;
        }

        if ($_FILES["image"]["size"] > 5000000) {
            $message = "Sorry, your file is too large.";
            $uploadOk = false;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = false;
        }

        if ($uploadOk) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO gallery (image_path, caption) VALUES (?, ?)");
                if ($stmt === false) {
                    die("Error preparing the statement: " . $conn->error);
                }
                $stmt->bind_param("ss", $target_file, $caption);
                if ($stmt->execute()) {
                    $message = "The image has been successfully uploaded and stored in the database.";
                    $uploadSuccess = true;
                } else {
                    $message = "Sorry, there was an error storing the image in the database.";
                }
                $stmt->close();
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Delete functionality
        $imagePath = $_POST['image_path'];

        // Delete the file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Prepare the SQL statement to delete the record from the database
        $stmt = $conn->prepare("DELETE FROM gallery WHERE image_path = ?");
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }
        $stmt->bind_param("s", $imagePath);
        if ($stmt->execute()) {
            $message = "The image has been successfully deleted.";
        } else {
            $message = "Sorry, there was an error deleting the image from the database.";
        }
        $stmt->close();
    }
}

// Fetch all images from the database
$images = [];
$result = $conn->query("SELECT * FROM gallery");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}
$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Manage Images</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="file"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .image-gallery .card {
            margin-bottom: 20px;
        }

        .image-gallery .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .image-gallery .card-body {
            padding: 10px;
        }

        .image-gallery .btn-danger {
            margin-top: 10px;
        }
    </style>
</head>
<body>
   

    <?php if (!empty($images)): ?>
        <div class="container mt-5">
            <div class="row image-gallery">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($image["image_path"]); ?>" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlspecialchars($image["caption"]); ?></p>
                                <form action="ManageGallery.php" method="post" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="image_path" value="<?php echo htmlspecialchars($image["image_path"]); ?>">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p class="mt-3">No images found.</p>
    <?php endif; ?>



    <form action="ManageGallery.php" method="post" enctype="multipart/form-data">
        <h2>Upload Image</h2>
        <label>Select image to upload:</label>
        <input type="file" name="image" required>
        <label>Caption:</label>
        <input type="text" name="caption" required>
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <?php if ($message): ?>
        <div class="alert <?php echo $uploadSuccess ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>




    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this image?");
        }
    </script>
</body>
</html>
