<?php
require_once '../includes/config.php';
require_once '../includes/contact_handler.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Check if message ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: messages.php');
    exit;
}

$messageId = (int)$_GET['id'];

// Get all contact messages to find the one we want
$allMessages = getContactMessages();
$message = null;

foreach ($allMessages as $msg) {
    if ($msg['id'] == $messageId) {
        $message = $msg;
        break;
    }
}

// If message not found, redirect back to messages page
if (!$message) {
    header('Location: messages.php');
    exit;
}

// Mark message as read if it's unread
if (!$message['is_read']) {
    markMessageAsRead($messageId);
    $message['is_read'] = true;
}

// Get unread count for sidebar
$unreadCount = count(getContactMessages(true));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message - Alshoaa Alahmr Admin</title>
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
        .message-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .message-body {
            padding: 20px;
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="messages.php">
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
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main content -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">View Message</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="messages.php">Messages</a></li>
                                    <li class="breadcrumb-item active">View Message</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo htmlspecialchars($message['subject'] ?: 'No Subject'); ?></h3>
                            <div class="card-tools">
                                <a href="messages.php" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Messages
                                </a>
                            </div>
                        </div>
                        <div class="message-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p><strong>Date:</strong> <?php echo date('F d, Y H:i', strtotime($message['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="message-body">
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn btn-primary">
                                <i class="fas fa-reply"></i> Reply via Email
                            </a>
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
