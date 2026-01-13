<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, 405);
}

// Get raw input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if ($data === null) {
    sendResponse(false, 'Invalid JSON format', null, 400);
}

if (!isset($data['username']) || !isset($data['password'])) {
    sendResponse(false, 'Username and password are required', null, 400);
}

$username = trim($data['username']);
$password = trim($data['password']);

if (empty($username) || empty($password)) {
    sendResponse(false, 'Username and password cannot be empty', null, 400);
}

// Use prepared statement to prevent SQL injection
$sql = "SELECT id, username, email, password, full_name, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    sendResponse(false, 'Database error: ' . $conn->error, null, 500);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendResponse(false, 'Username or password incorrect', null, 401);
}

$user = $result->fetch_assoc();

// Verify password using bcrypt
if (!password_verify($password, $user['password'])) {
    sendResponse(false, 'Username or password incorrect', null, 401);
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];
$_SESSION['login_time'] = time();

// Create secure cookie
setcookie('auth_token', session_id(), time() + 3600, '/', '', false, true);

$response = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'full_name' => $user['full_name'],
    'role' => $user['role'],
    'session_id' => session_id()
];

sendResponse(true, 'Login successful', $response, 200);

