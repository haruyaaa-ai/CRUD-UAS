<?php
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required = ['username', 'email', 'password', 'full_name', 'phone'];
foreach ($required as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        sendResponse(false, ucfirst($field) . ' is required', null, 400);
    }
}

$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$password = $data['password'];
$full_name = $conn->real_escape_string($data['full_name']);
$phone = $conn->real_escape_string($data['phone']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Invalid email format', null, 400);
}

// Check if username already exists
$checkUsername = $conn->query("SELECT id FROM users WHERE username = '$username'");
if ($checkUsername->num_rows > 0) {
    sendResponse(false, 'Username already exists', null, 409);
}

// Check if email already exists
$checkEmail = $conn->query("SELECT id FROM users WHERE email = '$email'");
if ($checkEmail->num_rows > 0) {
    sendResponse(false, 'Email already exists', null, 409);
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert new user
$sql = "INSERT INTO users (username, email, password, full_name, phone, role) 
        VALUES (?, ?, ?, ?, ?, 'customer')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $email, $hashedPassword, $full_name, $phone);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    $response = [
        'user_id' => $user_id,
        'username' => $username,
        'email' => $email,
        'full_name' => $full_name,
        'role' => 'customer'
    ];
    sendResponse(true, 'Registration successful', $response, 201);
} else {
    sendResponse(false, 'Registration failed: ' . $stmt->error, null, 500);
}
?>
