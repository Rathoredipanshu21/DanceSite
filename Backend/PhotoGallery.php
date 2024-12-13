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

// Fetch all images and captions from the database
$sql = "SELECT image_path, caption FROM gallery";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error: " . $conn->error); // Output the MySQL error if the query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
  <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 2.5rem;
    color: #333;
}

.gallery {
    display: grid;
    grid-template-columns: repeat(1, 1fr); /* Default to 1 column on small screens */
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto; /* Center the gallery */
}

.gallery-item {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for card effect */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-10px); /* Slight lift on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
    width: 100%; /* Ensure full width */
    height: 50vh; /* Set a fixed height */
    object-fit: contain; /* Ensure the full image is shown */
    display: block;
    background-color: #f4f4f4; /* Optional background color if the image doesn't fill the container */
}

.caption {
    text-align: center;
    padding: 15px;
    font-size: 1rem;
    color: #555;
}

/* For desktop screens (larger than 768px) */
@media (min-width: 768px) {
    .gallery {
        grid-template-columns: repeat(3, 1fr); /* 3 columns on desktop */
    }
}

  </style>
</head>
<body>
    <h1>Photo Gallery</h1>
    <div class="gallery">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='gallery-item'>";
                echo "<img src='" . $row["image_path"] . "' alt='Image'>";
                echo "<div class='caption'>" . $row["caption"] . "</div>";
                echo "</div>";
            }
        } else {
            echo "No images found.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
