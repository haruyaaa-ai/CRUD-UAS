<?php
require_once '../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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
    $checkSql = "SELECT id FROM reservasi WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
} else {
    $checkSql = "SELECT id FROM reservasi WHERE id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $id, $user_id);
}

$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    sendResponse(false, 'Reservasi not found or unauthorized', null, 404);
}

$sql = "DELETE FROM reservasi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    sendResponse(true, 'Reservasi deleted successfully', ['id' => $id], 200);
} else {
    sendResponse(false, 'Failed to delete reservasi: ' . $stmt->error, null, 500);
}
?>
