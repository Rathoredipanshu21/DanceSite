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
    $videos = []; // Ensure $videos is an empty array if no results
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Control and Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 40px;
        }

        label {
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        input[type="text"], textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"], .delete-button {
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .delete-button {
            background-color: red;
        }

        input[type="submit"]:hover, .delete-button:hover {
            background-color: darkgreen;
        }

        .delete-button:hover {
            background-color: darkred;
        }

        /* Video container styling */
        .video-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 10px;
            text-align: center;
            margin-bottom: 20px; /* Ensure space between video items */
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 items per row on larger screens */
            gap: 20px; /* Space between grid items */
        }

        iframe {
            width: 100%; /* Make the video take up the full width of the container */
            height: 200px; /* Adjust the height as needed */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .caption {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .delete-button {
            padding: 8px 15px;
            font-size: 14px;
            background-color: red;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .video-grid {
                grid-template-columns: repeat(1, 1fr); /* 1 item per row on smaller screens */
            }
            iframe {
                height: 180px; /* Adjust for smaller screens */
            }
        }

        @media (max-width: 576px) {
            iframe {
                height: 160px; /* Adjust for very small screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Videos</h1>
        
        <!-- Form to add video details -->
        <form method="POST" action="">
            <label for="id">Video ID:</label>
            <input type="text" id="id" name="id" placeholder="Enter video ID (optional)">
            
            <label for="url">Video URL:</label>
            <input type="text" id="url" name="url" required placeholder="Google Drive shareable link">
            
            <label for="caption">Caption:</label>
            <input type="text" id="caption" name="caption" required placeholder="Enter video caption">
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required placeholder="Enter video description"></textarea>
            
            <input type="submit" value="Add Video">
        </form>
        
        <!-- Display existing videos -->
        <?php if (!empty($videos)): ?>
            <h1>Video Gallery</h1>
            <div class="video-grid">
                <?php foreach ($videos as $video): ?>
                    <div class="video-container">
                        <iframe src="<?php echo htmlspecialchars($video['url']); ?>" allow="autoplay"></iframe>
                        <div class="caption"><?php echo htmlspecialchars($video['caption']); ?></div>
                        <p><?php echo htmlspecialchars($video['description']); ?></p>
                        <!-- Delete button -->
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                            <input type="hidden" name="delete_id" value="<?php echo $video['id']; ?>">
                            <button type="submit" class="delete-button">Delete Video</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No videos available</p>
        <?php endif; ?>
    </div>
</body>
</html>
