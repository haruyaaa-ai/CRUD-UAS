<?php
require_once '../../config/database.php';

// Check Admin Auth
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    sendResponse(false, 'Unauthorized', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
     // Allow POST custom method DELETE for simplicity
     $input = json_decode(file_get_contents("php://input"), true);
     if(!isset($input['method']) || $input['method'] !== 'DELETE') {
         sendResponse(false, 'Method Not Allowed', null, 405);
     }
} 
else {
    $input = json_decode(file_get_contents("php://input"), true);
}

// Support GET/POST param id too if easier
$id = $_GET['id'] ?? $input['id'] ?? null;

if (!$id) {
    sendResponse(false, 'ID required', null, 400);
}

// Get Image Path First to Delete File
$sql = "SELECT image FROM berita WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

// Delete Record
$delSql = "DELETE FROM berita WHERE id = ?";
$delStmt = $conn->prepare($delSql);
$delStmt->bind_param("i", $id);

if ($delStmt->execute()) {
    // Delete File if not default
    if ($row && $row['image'] && strpos($row['image'], 'default') === false) {
        $filePath = '../../' . $row['image'];
        if (file_exists($filePath)) unlink($filePath);
    }
    sendResponse(true, 'Berita berhasil dihapus');
} else {
    sendResponse(false, 'Gagal menghapus berita');
}
?>
