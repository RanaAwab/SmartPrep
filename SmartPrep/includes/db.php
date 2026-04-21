<?php
require_once __DIR__ . '/../config.php';

/**
 * Get PDO instance (singleton style)
 */
function db() {
    static $pdoInstance = null;

    if ($pdoInstance === null) {
        global $pdo;
        $pdoInstance = $pdo;
    }

    return $pdoInstance;
}

/**
 * Run SELECT query
 */
function fetchAll($query, $params = []) {
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Run SELECT single row
 */
function fetch($query, $params = []) {
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Run INSERT / UPDATE / DELETE
 */
function executeQuery($query, $params = []) {
    $stmt = db()->prepare($query);
    return $stmt->execute($params);
}
?>