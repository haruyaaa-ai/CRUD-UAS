<?php
require_once '../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || empty($data['id'])) {
    sendResponse(false, 'ID is required', null, 400);
}

$id = intval($data['id']);
$user_id = $_SESSION['user_id'];

// Check if reservasi exists and belongs to user
// (Admin juga bisa confirm payment sih, tapi ini konteksnya user bayar sendiri)
$checkSql = "SELECT id, status, total_price FROM reservasi WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    sendResponse(false, 'Reservasi not found or unauthorized', null, 404);
}

$reservasi = $result->fetch_assoc();

if ($reservasi['status'] === 'confirmed') {
    sendResponse(true, 'Payment already confirmed', ['id' => $id, 'status' => 'confirmed'], 200);
}

// Simulate Payment Process (In real world, verify gateway signature)
// Here we just approve it directly as per User Request "otomatis masuk riwayat"

$updateSql = "UPDATE reservasi SET status = 'confirmed' WHERE id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param("i", $id);

if ($updateStmt->execute()) {
    sendResponse(true, 'Payment confirmed successfully', ['id' => $id, 'status' => 'confirmed'], 200);
} else {
    sendResponse(false, 'Failed to confirm payment', null, 500);
}
?>
