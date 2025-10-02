<?php
// Database connection settings for MariaDB
$host = '127.0.0.1';
$username = 'root';
$password = ''; // Add your root password here if you have one set

echo "Admin Password Reset Tool\n";
echo "=======================\n\n";

try {
    // Connect to MariaDB server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MariaDB successfully!\n";
    
    // Check if database exists, create if not
    $stmt = $conn->query("SHOW DATABASES LIKE 'alshoaa_alahmr'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("CREATE DATABASE alshoaa_alahmr");
        echo "Created database 'alshoaa_alahmr'\n";
    }
    
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=alshoaa_alahmr", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users table exists, create if not
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
        echo "Created 'users' table\n";
    }
    
    // Check if contact_messages table exists, create if not
    $stmt = $conn->query("SHOW TABLES LIKE 'contact_messages'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200),
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_read BOOLEAN DEFAULT FALSE
        )";
        $conn->exec($sql);
        echo "Created 'contact_messages' table\n";
    }
    
    // Check if admin user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Admin exists, reset password
        $adminId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        $newPassword = 'admin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $adminId]);
        
        echo "Admin password has been reset!\n";
    } else {
        // Admin doesn't exist, create one
        $adminUsername = 'admin';
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $adminEmail = 'admin@alshoaa.ly';
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$adminUsername, $adminPassword, $adminEmail]);
        
        echo "Admin user created successfully!\n";
    }
    
    echo "\nAdmin login credentials:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    
    // Create database user for the website if not exists
    try {
        $conn->exec("CREATE USER IF NOT EXISTS 'alshoaa_user'@'localhost' IDENTIFIED BY 'alshoaa_password'");
        $conn->exec("GRANT SELECT, INSERT, UPDATE, DELETE ON alshoaa_alahmr.* TO 'alshoaa_user'@'localhost'");
        $conn->exec("FLUSH PRIVILEGES");
        echo "\nDatabase user 'alshoaa_user' created and configured\n";
    } catch (PDOException $e) {
        echo "\nCould not create database user (may require higher privileges)\n";
    }
    
    echo "\nDatabase setup complete!\n";
    
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Make sure your MariaDB server is running\n";
    echo "2. Check that the root user has the correct password\n";
    echo "3. Verify that the root user has privileges to create databases\n";
}
?>
