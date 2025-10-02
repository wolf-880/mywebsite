<?php
require_once 'config.php';

/**
 * Get database connection
 * @return PDO Database connection object
 */
function getDbConnection() {
    static $db = null;
    
    if ($db === null) {
        try {
            $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log('Connection Error: ' . $e->getMessage());
            die('Sorry, there was a problem connecting to the database.');
        }
    }
    
    return $db;
}

/**
 * Execute a query and return all results
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array Results of the query
 */
function dbQuery($sql, $params = []) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log('Query Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Execute a query and return a single row
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|bool Single row or false on failure
 */
function dbQuerySingle($sql, $params = []) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log('Query Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Execute an INSERT, UPDATE, or DELETE query
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int|bool Number of affected rows or false on failure
 */
function dbExecute($sql, $params = []) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch(PDOException $e) {
        error_log('Execute Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get the ID of the last inserted row
 * @return string Last inserted ID
 */
function dbLastInsertId() {
    return getDbConnection()->lastInsertId();
}

/**
 * Sanitize input data
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
