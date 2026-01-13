<?php
require_once '../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Not authenticated', null, 401);
}

// Check if session is still valid (3600 seconds = 1 hour)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 3600) {
    session_destroy();
    sendResponse(false, 'Session expired', null, 401);
}

// Refresh session time
$_SESSION['login_time'] = time();

$response = [
    'user_id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'email' => $_SESSION['email'],
    'full_name' => $_SESSION['full_name'],
    'role' => $_SESSION['role'],
    'is_logged_in' => true
];

sendResponse(true, 'Session valid', $response, 200);
?>
