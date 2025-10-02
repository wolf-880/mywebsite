<?php
// Database connection settings for MariaDB
$host = '127.0.0.1';
$username = 'root';
$password = ''; // Add your root password here if you have one set

echo "<h1>Database Connection Test</h1>";

try {
    // Connect to MariaDB server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'>✓ Connected to MariaDB server successfully!</p>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS alshoaa_alahmr";
    $conn->exec($sql);
    echo "<p style='color:green'>✓ Database 'alshoaa_alahmr' created or already exists</p>";
    
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=alshoaa_alahmr", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create contact_messages table
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_read BOOLEAN DEFAULT FALSE
    )";
    $conn->exec($sql);
    echo "<p style='color:green'>✓ Table 'contact_messages' created or already exists</p>";
    
    // Create users table for admin login
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "<p style='color:green'>✓ Table 'users' created or already exists</p>";
    
    // Check if admin user exists, create if not
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $adminPassword, 'admin@alshoaa.ly']);
        echo "<p style='color:green'>✓ Admin user created successfully</p>";
        echo "<p>Username: admin<br>Password: admin123</p>";
    } else {
        echo "<p style='color:green'>✓ Admin user already exists</p>";
    }
    
    // Create database user for the website
    try {
        $sql = "CREATE USER IF NOT EXISTS 'alshoaa_user'@'localhost' IDENTIFIED BY 'alshoaa_password'";
        $conn->exec($sql);
        echo "<p style='color:green'>✓ Database user 'alshoaa_user' created or already exists</p>";
        
        $sql = "GRANT SELECT, INSERT, UPDATE, DELETE ON alshoaa_alahmr.* TO 'alshoaa_user'@'localhost'";
        $conn->exec($sql);
        echo "<p style='color:green'>✓ Permissions granted to 'alshoaa_user'</p>";
    } catch (PDOException $e) {
        echo "<p style='color:orange'>⚠ Could not create database user (may require higher privileges): " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Next Steps</h2>";
    echo "<p>1. Make sure your website files are in the correct location for your web server</p>";
    echo "<p>2. Common web server document roots:</p>";
    echo "<ul>";
    echo "<li>XAMPP: C:\xampp\htdocs\</li>";
    echo "<li>WAMP: C:\wamp64\www\ or C:\wamp\www\</li>";
    echo "<li>IIS: C:\inetpub\wwwroot\</li>";
    echo "</ul>";
    echo "<p>3. You can either:</p>";
    echo "<ul>";
    echo "<li>Copy your website files to one of these locations, or</li>";
    echo "<li>Configure your web server to point to D:\alshoaa alahmr\</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color:red'>✗ Connection failed: " . $e->getMessage() . "</p>";
}
?>
