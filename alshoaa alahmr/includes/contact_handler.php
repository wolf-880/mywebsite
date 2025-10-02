<?php
require_once 'db_functions.php';

/**
 * Save a contact form submission to the database
 * @param string $name Sender's name
 * @param string $email Sender's email
 * @param string $subject Message subject
 * @param string $message Message content
 * @return bool True on success, false on failure
 */
function saveContactMessage($name, $email, $subject, $message) {
    // Sanitize inputs
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $subject = sanitizeInput($subject);
    $message = sanitizeInput($message);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        return false;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Insert into database
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $params = [$name, $email, $subject, $message];
    
    return dbExecute($sql, $params) ? true : false;
}

/**
 * Get all contact messages
 * @param bool $unreadOnly Get only unread messages if true
 * @return array Array of contact messages
 */
function getContactMessages($unreadOnly = false) {
    $sql = "SELECT * FROM contact_messages";
    $params = [];
    
    if ($unreadOnly) {
        $sql .= " WHERE is_read = 0";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    return dbQuery($sql, $params);
}

/**
 * Mark a message as read
 * @param int $messageId ID of the message to mark as read
 * @return bool True on success, false on failure
 */
function markMessageAsRead($messageId) {
    $sql = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
    $params = [(int)$messageId];
    
    return dbExecute($sql, $params) ? true : false;
}
