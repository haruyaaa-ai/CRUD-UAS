<?php
require_once '../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || empty($data['id'])) {
    sendResponse(false, 'ID is required', null, 400);
}

$id = intval($data['id']);
$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['role'] === 'admin';

// Check if reservasi exists and user has permission
if ($is_admin) {
    $checkSql = "SELECT id, user_id FROM reservasi WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
} else {
    $checkSql = "SELECT id, user_id FROM reservasi WHERE id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $id, $user_id);
}

$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    sendResponse(false, 'Reservasi not found or unauthorized', null, 404);
}

// Build update query
$updates = [];
$params = [];
$types = '';

if (isset($data['name'])) {
    $updates[] = "name = ?";
    $name = $conn->real_escape_string($data['name']);
    $params[] = $name;
    $types .= 's';
}

if (isset($data['email'])) {
    $updates[] = "email = ?";
    $email = $conn->real_escape_string($data['email']);
    $params[] = $email;
    $types .= 's';
}

if (isset($data['phone'])) {
    $updates[] = "phone = ?";
    $phone = $conn->real_escape_string($data['phone']);
    $params[] = $phone;
    $types .= 's';
}

if (isset($data['date_booking'])) {
    $updates[] = "date_booking = ?";
    $date = $conn->real_escape_string($data['date_booking']);
    $params[] = $date;
    $types .= 's';
}

if (isset($data['tickets'])) {
    $updates[] = "tickets = ?";
    $tickets = intval($data['tickets']);
    $params[] = $tickets;
    $types .= 'i';
}

// Only admin can change status
if (isset($data['status']) && $is_admin) {
    $updates[] = "status = ?";
    $status = $conn->real_escape_string($data['status']);
    $params[] = $status;
    $types .= 's';
}

if (empty($updates)) {
    sendResponse(false, 'No fields to update', null, 400);
}

$params[] = $id;
$types .= 'i';

$sql = "UPDATE reservasi SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    sendResponse(true, 'Reservasi updated successfully', ['id' => $id], 200);
} else {
    sendResponse(false, 'Failed to update reservasi: ' . $stmt->error, null, 500);
}
?>
