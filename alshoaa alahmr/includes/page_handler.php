<?php
require_once 'db_functions.php';

/**
 * Get a page by its slug
 * @param string $slug Page slug
 * @return array|bool Page data or false if not found
 */
function getPageBySlug($slug) {
    $sql = "SELECT * FROM pages WHERE slug = ?";
    $params = [sanitizeInput($slug)];
    
    return dbQuerySingle($sql, $params);
}

/**
 * Get a page by its ID
 * @param int $pageId Page ID
 * @return array|bool Page data or false if not found
 */
function getPageById($pageId) {
    $sql = "SELECT * FROM pages WHERE id = ?";
    $params = [(int)$pageId];
    
    return dbQuerySingle($sql, $params);
}

/**
 * Get all pages
 * @return array Array of pages
 */
function getAllPages() {
    $sql = "SELECT * FROM pages ORDER BY title ASC";
    
    return dbQuery($sql);
}

/**
 * Update a page's content
 * @param int $pageId Page ID to update
 * @param string $title Page title
 * @param string $content Page content
 * @return bool True on success, false on failure
 */
function updatePage($pageId, $title, $content) {
    // Sanitize inputs
    $title = sanitizeInput($title);
    // Don't sanitize content here as it may contain HTML
    
    // Validate inputs
    if (empty($title) || empty($content)) {
        return false;
    }
    
    // Update database
    $sql = "UPDATE pages SET title = ?, content = ? WHERE id = ?";
    $params = [$title, $content, (int)$pageId];
    
    return dbExecute($sql, $params) ? true : false;
}

/**
 * Create a new page
 * @param string $title Page title
 * @param string $content Page content
 * @param string $slug Page slug
 * @return int|bool New page ID or false on failure
 */
function createPage($title, $content, $slug) {
    // Sanitize inputs
    $title = sanitizeInput($title);
    $slug = sanitizeInput($slug);
    // Don't sanitize content here as it may contain HTML
    
    // Validate inputs
    if (empty($title) || empty($content) || empty($slug)) {
        return false;
    }
    
    // Check if slug already exists
    $existingPage = getPageBySlug($slug);
    if ($existingPage) {
        return false;
    }
    
    // Insert into database
    $sql = "INSERT INTO pages (title, content, slug) VALUES (?, ?, ?)";
    $params = [$title, $content, $slug];
    
    if (dbExecute($sql, $params)) {
        return dbLastInsertId();
    }
    
    return false;
}
