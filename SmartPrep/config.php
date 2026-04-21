<?php
// ======================================
// ⚙️ APP SETTINGS
// ======================================
// Main application metadata
define('APP_NAME', 'SmartPrep');
define('BASE_URL', 'http://localhost/SmartPrep/');
define('ENV', 'development'); // Options: 'development' or 'production'
define('APP_TIMEZONE', 'Asia/Karachi'); // Define your local timezone

// Set Default Timezone universally across the app
date_default_timezone_set(APP_TIMEZONE);

// ======================================
// 🛑 ERROR HANDLING
// ======================================
if (ENV === 'development') {
    // Show all errors during development
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Hide errors from user in production, but log them
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error_log.txt');
}

// ======================================
// 🔐 SESSION SECURITY
// ======================================
// Prevent JavaScript from accessing session cookies (XSS protection)
ini_set('session.cookie_httponly', 1);
// Force sessions to use only cookies (prevent session ID in URLs)
ini_set('session.use_only_cookies', 1);
// Prevent session fixation attacks
ini_set('session.use_strict_mode', 1);

// Start session securely if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================
// 🗄 DATABASE CONFIG
// ======================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smartprep');

// ======================================
// 🔌 DATABASE CONNECTION (PDO - PRO)
// ======================================
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            // Throw exceptions on DB errors
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Fetch associative arrays by default
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Disable emulated prepares for better security & performance
            PDO::ATTR_EMULATE_PREPARES   => false, 
        ]
    );
} catch (PDOException $e) {
    // Handle error gracefully based on the environment
    if (ENV === 'development') {
        die("<h2>Database Connection Failed!</h2><p>" . $e->getMessage() . "</p>");
    } else {
        error_log("DB Connection Error: " . $e->getMessage());
        die("<h2>Server Error</h2><p>A database error occurred. Please try again later.</p>");
    }
}

// ======================================
// 🛠️ HELPER FUNCTIONS
// ======================================

/**
 * Generate absolute URL for the application securely
 *
 * @param string $path Path to append to the base URL
 * @return string Full resolved URL
 */
if (!function_exists('base_url')) {
    function base_url($path = '') {
        // Automatically trims trailing/leading slashes to prevent double routing slashes '//'
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}
?>