<?php
// Database connection settings
$host = 'localhost';
$dbname = 'alshoaa_alahmr';
$username = 'alshoaa_user';
$password = 'alshoaa_password'; // Password for the alshoaa_user database user

// Create database connection
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log the error instead of displaying it (for security)
    error_log('Connection Error: ' . $e->getMessage());
    die('Sorry, there was a problem connecting to the database.');
}
