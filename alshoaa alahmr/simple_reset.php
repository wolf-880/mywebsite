<?php
// Simple script to reset admin password

// Connect directly to the database
$host = 'localhost';
$dbname = 'alshoaa_alahmr';
$username = 'alshoaa_user';
$password = 'alshoaa_password';

// The admin credentials we want to set
$adminUsername = 'admin';
$adminPassword = 'admin123'; // Or use 'adminadmin' if you prefer

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hash the password properly
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    
    // Check if admin exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$adminUsername]);
    
    if ($stmt->rowCount() > 0) {
        // Admin exists, update password
        $adminId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $adminId]);
        
        echo "<p>Admin password has been reset!</p>";
    } else {
        // Admin doesn't exist, create one
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$adminUsername, $hashedPassword, 'admin@alshoaa.ly']);
        
        echo "<p>Admin user created successfully!</p>";
    }
    
    echo "<p><strong>Username:</strong> $adminUsername<br><strong>Password:</strong> $adminPassword</p>";
    echo "<p>You can now <a href='admin/index.php'>log in to the admin panel</a>.</p>";
    
} catch(PDOException $e) {
    echo "<p>Database error: " . $e->getMessage() . "</p>";
}
?>
