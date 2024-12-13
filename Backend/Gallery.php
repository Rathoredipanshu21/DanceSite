<?php
// Database credentials
$servername = "localhost";
$username = "root"; // Use your database username
$password = ""; // Use your database password
$dbname = "login_system"; // Use your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request was made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']); // Sanitize input
    $delete_sql = "DELETE FROM videos WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    
    if ($stmt->execute()) {
        echo "Video deleted successfully!";
    } else {
        echo "Error deleting video: " . $conn->error;
    }
    $stmt->close();
}

// Fetch videos from the database
$sql = "SELECT id, url, caption, description FROM videos";
$result = $conn->query($sql);

$videos = [];
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Videos</title>
    <style>
        /* Basic styling for video container */
        .video-container {
            margin: 20px 0;
            text-align: center;
        }

        video, iframe {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 600px;
            height: 315px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .delete-button {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .delete-button:hover {
            background-color: darkred;
        }
        .content{
            display: flex;
            justify-content:space-around;
        }
        .video-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 items per row on larger screens */
    gap: 20px; /* Space between grid items */
}

@media (max-width: 768px) {
    .video-grid {
        grid-template-columns: repeat(1, 1fr); /* 1 item per row on smaller screens */
    }
}

    </style>
</head>
<body>
    <h1 style="text-align:center">Welcome to the Dance Gallery</h1>
    <div class="content">

        <!-- Loop through the video data and display each video with delete button -->
        <?php foreach ($videos as $video): ?>
            <div class="video-container">
                <h2><?php echo htmlspecialchars($video['caption']); ?></h2>
                <p><?php echo htmlspecialchars($video['description']); ?></p>

                <!-- Embed the video using an iframe for Google Drive preview links -->
                <iframe src="<?php echo htmlspecialchars($video['url']); ?>" allow="autoplay"></iframe>

                <!-- Delete form -->
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                    <input type="hidden" name="delete_id" value="<?php echo $video['id']; ?>">
                    <button type="submit" class="delete-button">Delete Video</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>