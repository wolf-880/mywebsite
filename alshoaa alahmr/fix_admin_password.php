<?php
// Include necessary files
require_once 'includes/config.php';
require_once 'includes/db_functions.php';

// Set admin credentials
$adminUsername = 'admin';
$adminPassword = 'adminadmin';
$adminEmail = 'admin@alshoaa.ly';

// Hash the password properly
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

// Check if admin user exists
$sql = "SELECT id FROM users WHERE username = ?";
$params = [$adminUsername];
$existingUser = dbQuerySingle($sql, $params);

if ($existingUser) {
    // Admin exists, update password
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $params = [$hashedPassword, $adminUsername];
    
    if (dbExecute($sql, $params)) {
        echo "✓ Admin password has been reset successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "You can now <a href='admin/index.php'>log in to the admin panel</a>.";
    } else {
        echo "✗ Failed to update admin password.";
    }
} else {
    // Admin doesn't exist, create new admin user
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $params = [$adminUsername, $hashedPassword, $adminEmail];
    
    if (dbExecute($sql, $params)) {
        echo "✓ Admin user created successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "You can now <a href='admin/index.php'>log in to the admin panel</a>.";
    } else {
        echo "✗ Failed to create admin user.";
    }
}
?>
