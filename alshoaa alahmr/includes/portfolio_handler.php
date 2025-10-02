<?php
require_once 'db_functions.php';

/**
 * Get all portfolio items
 * @param string $category Optional category filter
 * @return array Array of portfolio items
 */
function getPortfolioItems($category = null) {
    $sql = "SELECT * FROM portfolio_items";
    $params = [];
    
    if ($category) {
        $sql .= " WHERE category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    return dbQuery($sql, $params);
}

/**
 * Get a single portfolio item by ID
 * @param int $itemId Portfolio item ID
 * @return array|bool Portfolio item data or false if not found
 */
function getPortfolioItem($itemId) {
    $sql = "SELECT * FROM portfolio_items WHERE id = ?";
    $params = [(int)$itemId];
    
    return dbQuerySingle($sql, $params);
}

/**
 * Add a new portfolio item
 * @param string $title Item title
 * @param string $description Item description
 * @param string $imagePath Path to the item image
 * @param string $category Item category
 * @return int|bool New item ID or false on failure
 */
function addPortfolioItem($title, $description, $imagePath, $category) {
    // Sanitize inputs
    $title = sanitizeInput($title);
    $description = sanitizeInput($description);
    $imagePath = sanitizeInput($imagePath);
    $category = sanitizeInput($category);
    
    // Validate inputs
    if (empty($title) || empty($description)) {
        return false;
    }
    
    // Insert into database
    $sql = "INSERT INTO portfolio_items (title, description, image_path, category) VALUES (?, ?, ?, ?)";
    $params = [$title, $description, $imagePath, $category];
    
    if (dbExecute($sql, $params)) {
        return dbLastInsertId();
    }
    
    return false;
}

/**
 * Update an existing portfolio item
 * @param int $itemId Item ID to update
 * @param string $title Item title
 * @param string $description Item description
 * @param string $imagePath Path to the item image
 * @param string $category Item category
 * @return bool True on success, false on failure
 */
function updatePortfolioItem($itemId, $title, $description, $imagePath, $category) {
    // Sanitize inputs
    $title = sanitizeInput($title);
    $description = sanitizeInput($description);
    $imagePath = sanitizeInput($imagePath);
    $category = sanitizeInput($category);
    
    // Validate inputs
    if (empty($title) || empty($description)) {
        return false;
    }
    
    // Update database
    $sql = "UPDATE portfolio_items SET title = ?, description = ?, image_path = ?, category = ? WHERE id = ?";
    $params = [$title, $description, $imagePath, $category, (int)$itemId];
    
    return dbExecute($sql, $params) ? true : false;
}

/**
 * Delete a portfolio item
 * @param int $itemId Item ID to delete
 * @return bool True on success, false on failure
 */
function deletePortfolioItem($itemId) {
    $sql = "DELETE FROM portfolio_items WHERE id = ?";
    $params = [(int)$itemId];
    
    return dbExecute($sql, $params) ? true : false;
}
