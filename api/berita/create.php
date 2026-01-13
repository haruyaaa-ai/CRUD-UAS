<?php
require_once '../../config/database.php';

// Check Admin Auth
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    sendResponse(false, 'Unauthorized', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method Not Allowed', null, 405);
}

$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$content = $_POST['content'] ?? '';
$created_by = $_SESSION['user_id'];

if (empty($title) || empty($content) || empty($category)) {
    sendResponse(false, 'Title, content, and category are required', null, 400);
}

// Handle Image Upload
$imagePath = 'assets/img/default-news.jpg'; // Default

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../uploads/news/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid('news_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
        $imagePath = 'uploads/news/' . $fileName;
    }
}

$sql = "INSERT INTO berita (title, category, content, image, created_by) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $title, $category, $content, $imagePath, $created_by);

if ($stmt->execute()) {
    sendResponse(true, 'Berita berhasil ditambahkan', ['id' => $stmt->insert_id]);
} else {
    sendResponse(false, 'Gagal menambah berita: ' . $stmt->error);
}
?>
