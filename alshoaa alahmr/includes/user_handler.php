<?php
require_once 'db_functions.php';

/**
 * Register a new user
 * @param string $username Username
 * @param string $password Password
 * @param string $email Email address
 * @return int|bool New user ID or false on failure
 */
function registerUser($username, $password, $email) {
    // Sanitize inputs
    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    
    // Validate inputs
    if (empty($username) || empty($password) || empty($email)) {
        return false;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Check if username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $params = [$username, $email];
    $existingUser = dbQuerySingle($sql, $params);
    
    if ($existingUser) {
        return false; // User already exists
    }
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert into database
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $params = [$username, $hashedPassword, $email];
    
    if (dbExecute($sql, $params)) {
        return dbLastInsertId();
    }
    
    return false;
}

/**
 * Authenticate a user
 * @param string $username Username
 * @param string $password Password
 * @return array|bool User data on success, false on failure
 */
function loginUser($username, $password) {
    // Sanitize input
    $username = sanitizeInput($username);
    
    // Get user from database
    $sql = "SELECT * FROM users WHERE username = ?";
    $params = [$username];
    $user = dbQuerySingle($sql, $params);
    
    if (!$user) {
        return false; // User not found
    }
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Remove password from user data before returning
        unset($user['password']);
        return $user;
    }
    
    return false; // Password incorrect
}

/**
 * Get user by ID
 * @param int $userId User ID
 * @return array|bool User data or false if not found
 */
function getUserById($userId) {
    $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
    $params = [(int)$userId];
    
    return dbQuerySingle($sql, $params);
}

/**
 * Update user password
 * @param int $userId User ID
 * @param string $currentPassword Current password
 * @param string $newPassword New password
 * @return bool True on success, false on failure
 */
function updateUserPassword($userId, $currentPassword, $newPassword) {
    // Get user from database
    $sql = "SELECT password FROM users WHERE id = ?";
    $params = [(int)$userId];
    $user = dbQuerySingle($sql, $params);
    
    if (!$user) {
        return false; // User not found
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $user['password'])) {
        return false; // Current password incorrect
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password in database
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $params = [$hashedPassword, (int)$userId];
    
    return dbExecute($sql, $params) ? true : false;
}
