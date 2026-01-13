<?php
require_once '../../config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : null;

if ($id) {
    // Single Detail
    $sql = "SELECT b.*, u.full_name as author_name 
            FROM berita b 
            LEFT JOIN users u ON b.created_by = u.id 
            WHERE b.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if ($data) {
        sendResponse(true, 'Berita found', $data);
    } else {
        sendResponse(false, 'Berita not found', null, 404);
    }
} else {
    // List
    $sql = "SELECT b.*, u.full_name as author_name 
            FROM berita b 
            LEFT JOIN users u ON b.created_by = u.id 
            WHERE 1=1";
            
    if ($category) {
        $sql .= " AND category = '$category'";
    }
    
    $sql .= " ORDER BY date DESC LIMIT 20";
    
    $result = $conn->query($sql);

    if (!$result) {
        // Jika query gagal (misal tabel belum ad atau syntax error), kembalikan error dulu
        // Tetapi sebaiknya jangan mati, return empty array atau error message
        sendResponse(false, 'DB Error: ' . $conn->error);
    }
    
    // AUTO-SEED (Self Healing) if empty and no specific category filter
    if ($result->num_rows == 0 && empty($category)) {
         // Get Admin ID safely
         $adm = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
         // Handle if adm query fails
         $adminId = ($adm && $adm->num_rows > 0) ? $adm->fetch_assoc()['id'] : 1;

         $insertSql = "INSERT INTO berita (title, content, category, date, created_by, image) VALUES 
            ('Laporan Konservasi Gajah', 'Populasi Gajah Sumatera terpantau stabil di zona inti. Tim patroli menemukan jejak bayi gajah baru.', 'Konservasi', '2025-05-10', $adminId, 'assets/img/default-news.jpg'),
            ('Studi Keragaman Flora', 'Peneliti menemukan spesies anggrek langka di sektor utara taman nasional.', 'Riset', '2025-04-22', $adminId, 'assets/img/default-news.jpg'),
            ('Ekowisata Kembali Dibuka', 'Jalur treking sepeda kini dibuka kembali untuk umum dengan protokol konservasi baru.', 'Ekowisata', '2025-06-01', $adminId, 'assets/img/default-news.jpg')";
         
         $conn->query($insertSql);
         // Re-fetch
         $result = $conn->query($sql);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    sendResponse(true, 'List Berita', $data);
}
?>
