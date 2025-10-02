<?php
// Site configuration settings

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'alshoaa_alahmr');
define('DB_USER', 'alshoaa_user');
define('DB_PASS', 'alshoaa_password'); // Password for the alshoaa_user database user

// Website settings
define('SITE_NAME', 'Alshoaa Alahmr');
define('SITE_URL', 'http://localhost/alshoaa_alahmr'); // Change this to your actual URL in production

// Email settings for contact form
define('CONTACT_EMAIL', 'your_email@example.com'); // Change to your actual email

// Error reporting settings
ini_set('display_errors', 0); // Set to 1 during development, 0 in production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Session settings
session_start();
