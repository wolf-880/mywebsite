<?php
require_once '../includes/config.php';
require_once '../includes/user_handler.php';
require_once '../includes/contact_handler.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Get user data
$userId = $_SESSION['user_id'];
$user = getUserById($userId);

// Get unread contact messages count
$unreadMessages = getContactMessages(true);
$unreadCount = count($unreadMessages);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Alshoaa Alahmr</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar-header {
            padding: 0 15px 20px;
            border-bottom: 1px solid #495057;
            margin-bottom: 20px;
        }
        .sidebar-header h3 {
            color: #dc3545;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .nav-link:hover {
            color: #fff;
        }
        .nav-link.active {
            color: #fff;
            background-color: #495057;
        }
        .content-header {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .dashboard-card {
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .dashboard-card .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dashboard-card .card-icon {
            font-size: 3rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-header">
                    <h3>Alshoaa Alahmr</h3>
                    <p>Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php">
                            <i class="fas fa-envelope mr-2"></i> Messages
                            <?php if ($unreadCount > 0): ?>
                            <span class="badge badge-danger"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.php">
                            <i class="fas fa-image mr-2"></i> Portfolio
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Content -->
            <div class="col-md-9 col-lg-10">
                <div class="content-header">
                    <h2>Dashboard</h2>
                </div>
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-header">
                    <h3>Alshoaa Alahmr</h3>
                    <p>Admin Panel</p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php">
                            <i class="fas fa-envelope mr-2"></i> Messages
                            <?php if ($unreadCount > 0): ?>
                            <span class="badge badge-danger"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.php">
                            <i class="fas fa-image mr-2"></i> Portfolio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages.php">
                            <i class="fas fa-file-alt mr-2"></i> Pages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users mr-2"></i> Users
                        </a>
                    </li>
                    <li class="nav-item mt-5">
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card dashboard-card bg-info text-white">
                                <div class="card-body">
                                    <div>
                                        <h5 class="card-title">Messages</h5>
                                        <h2><?php echo $unreadCount; ?></h2>
                                        <p>Unread Messages</p>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="messages.php" class="text-white">View Details <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add more dashboard cards here -->
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Activities</h3>
                                </div>
                                <div class="card-body">
                                    <p>Welcome to your admin dashboard. Use the sidebar to navigate to different sections.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
