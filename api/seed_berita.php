<?php
require_once __DIR__ . '/../config/database.php';

// Force Insert Data jika kosong atau paksa tambah
$count = $conn->query("SELECT COUNT(*) as c FROM berita")->fetch_assoc()['c'];

echo "Jumlah Berita saat ini: " . $count . "<br>";

$adminId = 1; // Default
$users = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
if($users->num_rows > 0) $adminId = $users->fetch_assoc()['id'];

if ($count == 0) {
    $sql = "INSERT INTO berita (title, content, category, date, created_by, image) VALUES 
    ('Laporan Konservasi Gajah', 'Populasi Gajah Sumatera terpantau stabil di zona inti. Tim patroli menemukan jejak bayi gajah baru.', 'Konservasi', '2025-05-10', $adminId, 'assets/img/default-news.jpg'),
    ('Studi Keragaman Flora', 'Peneliti menemukan spesies anggrek langka di sektor utara taman nasional.', 'Riset', '2025-04-22', $adminId, 'assets/img/default-news.jpg'),
    ('Ekowisata Kembali Dibuka', 'Jalur treking sepeda kini dibuka kembali untuk umum dengan protokol konservasi baru.', 'Ekowisata', '2025-06-01', $adminId, 'assets/img/default-news.jpg')";
    
    if ($conn->query($sql)) {
        echo "Berhasil inject 3 berita dummy.<br>";
    } else {
        echo "Gagal inject: " . $conn->error . "<br>";
    }
} else {
    echo "Data sudah ada.<br>";
}

// Cek ulang
$res = $conn->query("SELECT * FROM berita");
while($row = $res->fetch_assoc()) {
    echo "- " . $row['title'] . " (" . $row['category'] . ")<br>";
}
?>
