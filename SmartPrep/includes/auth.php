<?php
require_once __DIR__ . '/../config.php';

/**
 * Check if the current user is logged in
 * Also incorporates basic session hijacking prevention by tracking IP and User Agent.
 * 
 * @return bool
 */
function isLoggedIn(): bool {
    if (isset($_SESSION['user_id'])) {
        
        // Prevent Session Hijacking: Verify User Agent
        $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $currentAgent) {
            logoutUser();
            return false;
        }

        // Prevent Session Hijacking: Verify IP Address 
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== $currentIp) {
            logoutUser();
            return false;
        }

        return true;
    }
    return false;
}

/**
 * Require a user to be logged in, otherwise redirect to login page
 * 
 * @return void
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header("Location: " . base_url('login.php'));
        exit();
    }
}

/**
 * Require a specific role to access the page (e.g., 'admin')
 * 
 * @param string $role The required role
 * @return void
 */
function requireRole(string $role): void {
    // Automatically enforce login
    requireLogin(); 
    
    if ($_SESSION['role'] !== $role) {
        renderForbidden();
    }
}

/**
 * Require at least one of the provided roles
 * 
 * @param array $roles Array of accepted roles
 * @return void
 */
function requireAnyRole(array $roles): void {
    // Automatically enforce login
    requireLogin();

    if (!in_array($_SESSION['role'], $roles, true)) {
        renderForbidden();
    }
}

/**
 * Authenticate and initialize a secure session for a user
 * 
 * @param array $user User data array from database
 * @return void
 */
function loginUser(array $user): void {
    // Regenerate session ID to prevent Session Fixation attacks
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['role']    = $user['role'];
    
    // Store fingerprint context to detect session hijacking anomalies later
    $_SESSION['user_ip']    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

/**
 * Completely destroy session and clear session cookies
 * 
 * @return void
 */
function logoutUser(): void {
    // Clear all session storage instantly
    $_SESSION = [];

    // Delete the active PHP session cookie payload
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy the session entirely server-side
    session_destroy();
}

/**
 * Safely redirect the current user based on their role
 * 
 * @return void
 */
function redirectByRole(): void {
    if (!isLoggedIn()) {
        header("Location: " . base_url('login.php'));
        exit();
    }

    $base = base_url();
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: {$base}admin/dashboard.php");
            break;
        case 'teacher':
            header("Location: {$base}teacher/dashboard.php");
            break;
        case 'student':
            header("Location: {$base}student/dashboard.php");
            break;
        default:
            // Fallback for corrupted or unknown roles
            logoutUser();
            header("Location: {$base}login.php?error=invalid_role");
            break;
    }
    exit();
}

/**
 * Render a graceful 403 Forbidden error response and halt execution
 * 
 * @return void
 */
function renderForbidden(): void {
    http_response_code(403);
    
    // Inline minimal styled 403 response keeping theme integrity
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>403 Forbidden - Access Denied</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap');
            body { font-family: 'Outfit', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: #1e293b; }
            .error-box { text-align: center; background: white; padding: 50px 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); border-top: 6px solid #ef4444; max-width: 450px; width: 90%; }
            .icon { font-size: 3.5rem; margin-bottom: 15px; display: block; }
            h1 { font-size: 2.2rem; margin: 0 0 10px 0; color: #0f172a; font-weight: 700; }
            p { color: #64748b; font-size: 1.05rem; margin-bottom: 30px; line-height: 1.5; }
            a { display: inline-flex; overflow:hidden; position: relative; padding: 12px 28px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: 600; transition: transform 0.2s, box-shadow 0.2s;}
            a:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3); }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <span class='icon'>🛑</span>
            <h1>Access Denied</h1>
            <p>You do not have the required permissions to view this page. Please return to your designated dashboard.</p>
            <a href='javascript:history.back()'>Go Back</a>
        </div>
    </body>
    </html>";
    exit();
}
?>