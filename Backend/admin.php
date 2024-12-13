<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* Basic styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            /* display: flex; */
            height: 100vh;
            overflow: hidden;
            flex-direction: column;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #333;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
        }

        /* Center the h1 text */
        .header h1 {
            margin: 0;
            flex: 1;
            text-align: center;
            font-size: 24px; /* Adjust the font size as needed */
        }

        .menu-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        .icon-container {
            display: flex;
            gap: 10px;
        }

        .icon-container i {
            font-size: 15px;
            cursor: pointer;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 40px;
            left: 0;
            width: 252px;
            height: calc(100% - 60px);
            background-color: #333;
            color: #fff;
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            padding: 15px;
            background-color: #444;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            padding: 15px;
        }

        .sidebar-menu li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 15px; /* Increased padding */
        }

        .sidebar-menu li a i {
            margin-right: 10px;
        }

        .sidebar-menu li a:hover {
            background-color: #575757;
        }

        /* Active list styling */
        .sidebar-menu li.active a {
            background-color: orangered;
            font-weight: bold;
            padding-left: 30px; /* Increased padding */
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            width: calc(100% - 250px);
            height: calc(100% - 60px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Dashboard Content */
        .dashboard {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dashboard-box-1 {
            background-color: blue;
        }

        .dashboard-box-2 {
            background-color: grey;
            color: black;
        }

        .dashboard-box-3 {
            background-color: green;
        }

        .dashboard-box-4 {
            background-color: red;
        }

        .dashboard-box {
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            min-width: 200px;
            color: #fff;
            text-align: center;
        }

        .graph-section {
            margin-top: 20px;
        }

        .graph-container {
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
        }

        .graph-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
        }

        /* Iframe for dynamic content */
        .iframe-container {
            flex: 1;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 250px;
            }

            .menu-btn {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <button id="menu-btn" class="menu-btn">&#9776; Menu</button>
        <h1>Admin Dashboard</h1>
        <div class="icon-container">
            <i class="fas fa-bell"></i> <!-- Notification icon -->
            <i class="fas fa-user"></i> <!-- User icon -->
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
            <hr style="color: orangered;">
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#" data-url="dashboard.html"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="#" data-url="rec_cls_signup.php"><i class="fas fa-plus"></i>Add Student</a></li>
            <li><a href="#" data-url="ManageStudent.php"><i class="fas fa-user"></i>Manage Students</a></li>
            <li><a href="#" data-url="VideoControl.php"><i class="fas fa-video"></i>Video Control</a></li>

            <li><a href="#" data-url="ManageGallery.php"><i class="fas fa-cog"></i>Manage Gallery</a></li>
            <li><a href="#" data-url="reports.html"><i class="fas fa-chart-line"></i>Reports</a></li>
            <li><a href="#" data-url="logout.html"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="main-content">
        <!-- Dashboard Content -->
        <div id="dashboard" class="content-section active">
            <div class="dashboard">
                <div class="dashboard-box dashboard-box-1">
                    <h2>Branch 1</h2>
                    <p>105 students</p>
                </div>
                <div class="dashboard-box dashboard-box-2">
                    <h2>Branch 2</h2>
                    <p>85 students</p>
                </div>
                <div class="dashboard-box dashboard-box-3">
                    <h2>Branch 3</h2>
                    <p>120 students</p>
                </div>
                <div class="dashboard-box dashboard-box-4">
                    <h2>Branch 4</h2>
                    <p>90 students</p>
                </div>
            </div>
            <div class="graph-section">
                <div class="graph-container">
                    <!-- <img src="https://plus.unsplash.com/premium_photo-1676673189412-56a98d221c11?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Z3JhcGh8ZW58MHx8MHx8fDA%3D" alt="Graph"> -->
                </div>
            </div>
        </div>

        <!-- Iframe for dynamic content -->
        <iframe id="iframe-content" class="iframe-container" style="display: none;"></iframe>
    </div>

    <!-- JavaScript -->
    <script>
        var sidebar = document.getElementById('sidebar');
        var mainContent = document.getElementById('main-content');
        var dashboard = document.getElementById('dashboard');
        var iframe = document.getElementById('iframe-content');
        var menuBtn = document.getElementById('menu-btn');

        // Handle sidebar link clicks to change iframe source and active state
        document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all links
                document.querySelectorAll('.sidebar-menu li').forEach(function(item) {
                    item.classList.remove('active');
                });

                // Add active class to the clicked link's parent li
                this.parentElement.classList.add('active');

                var targetUrl = link.getAttribute('data-url');

                // Check if it's the dashboard link
                if (targetUrl === 'dashboard.html') {
                    iframe.style.display = 'none';
                    dashboard.style.display = 'flex';
                } else {
                    iframe.style.display = 'block';
                    dashboard.style.display = 'none';
                    iframe.src = targetUrl;
                }
            });
        });

        // Handle menu button click to toggle sidebar
        menuBtn.addEventListener('click', function() {
            if (sidebar.style.transform === 'translateX(0px)') {
                sidebar.style.transform = 'translateX(-250px)';
                mainContent.style.marginLeft = '0';
            } else {
                sidebar.style.transform = 'translateX(0)';
                mainContent.style.marginLeft = '250px';
            }
        });
    </script>

</body>
</html>
