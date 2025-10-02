<?php
// Database connection settings for MariaDB
$host = '127.0.0.1';
$username = 'root';
$password = ''; // Add your root password here if you have one

try {
    // Connect to MariaDB server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Setup Script</h2>";
    echo "<p>Connected to MariaDB successfully!</p>";
    
    // Read the SQL file
    $sql = file_get_contents('database_setup.sql');
    
    // Execute the SQL commands
    $conn->exec($sql);
    
    echo "<p>Database and tables created successfully!</p>";
    
    // Create admin user with hashed password
    $conn = new PDO("mysql:host=$host;dbname=alshoaa_alahmr", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $adminUsername = 'admin';
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT); // Default password: admin123
    $adminEmail = 'admin@alshoaa.ly';
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$adminUsername, $adminEmail]);
    
    if ($stmt->rowCount() == 0) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$adminUsername, $adminPassword, $adminEmail]);
        echo "<p>Admin user created successfully!</p>";
        echo "<p>Username: admin<br>Password: admin123</p>";
        echo "<p><strong>Important:</strong> Please change this password after your first login!</p>";
    } else {
        echo "<p>Admin user already exists.</p>";
    }
    
    echo "<p>Database setup complete! You can now:</p>";
    echo "<ul>";
    echo "<li>Use the contact form to collect user messages</li>";
    echo "<li>Log in to the <a href='admin/index.php'>admin panel</a> to view messages</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
