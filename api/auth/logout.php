<?php
require_once '../../config/database.php';

// Destroy session
session_destroy();

// Delete cookie
setcookie('auth_token', '', time() - 3600, '/');

sendResponse(true, 'Logout successful', null, 200);
?>
