<?php
require_once '../../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendResponse(false, 'Unauthorized access', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['id']) || empty($data['id'])) {
    sendResponse(false, 'ID is required', null, 400);
}

$id = intval($data['id']);

// Check if berita exists
$checkStmt = $conn->prepare("SELECT id FROM berita WHERE id = ?");
$checkStmt->bind_param("i", $id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    sendResponse(false, 'Berita not found', null, 404);
}

// Build update query
$updates = [];
$params = [];
$types = '';

if (isset($data['title'])) {
    $updates[] = "title = ?";
    $title = $conn->real_escape_string($data['title']);
    $params[] = $title;
    $types .= 's';
}

if (isset($data['content'])) {
    $updates[] = "content = ?";
    $content = $conn->real_escape_string($data['content']);
    $params[] = $content;
    $types .= 's';
}

if (isset($data['category'])) {
    $updates[] = "category = ?";
    $category = $conn->real_escape_string($data['category']);
    $params[] = $category;
    $types .= 's';
}

if (empty($updates)) {
    sendResponse(false, 'No fields to update', null, 400);
}

$params[] = $id;
$types .= 'i';

$sql = "UPDATE berita SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    sendResponse(true, 'Berita updated successfully', ['id' => $id], 200);
} else {
    sendResponse(false, 'Failed to update berita: ' . $stmt->error, null, 500);
}
?>
