<?php
// Standalone Seed Script for CLI usage
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tesso_nilo_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin ID
$adminId = 1;
$res = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
if ($res && $res->num_rows > 0) {
    $adminId = $res->fetch_assoc()['id'];
}

// Check if empty
$count = $conn->query("SELECT COUNT(*) as c FROM berita")->fetch_assoc()['c'];
echo "Current News Count: $count\n";

if ($count == 0 || isset($argv[1])) {
    echo "Seeding data...\n";
    $sql = "INSERT INTO berita (title, content, category, date, created_by, image) VALUES 
    ('Laporan Konservasi Gajah', 'Populasi Gajah Sumatera terpantau stabil di zona inti. Tim patroli menemukan jejak bayi gajah baru.', 'Konservasi', '2025-05-10', $adminId, 'assets/img/default-news.jpg'),
    ('Studi Keragaman Flora', 'Peneliti menemukan spesies anggrek langka di sektor utara taman nasional.', 'Riset', '2025-04-22', $adminId, 'assets/img/default-news.jpg'),
    ('Ekowisata Kembali Dibuka', 'Jalur treking sepeda kini dibuka kembali untuk umum dengan protokol konservasi baru.', 'Ekowisata', '2025-06-01', $adminId, 'assets/img/default-news.jpg')";
    
    if ($conn->query($sql)) {
        echo "Success inserting dummy news.\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
} else {
    echo "Data already exists. Skipping.\n";
}
?>
