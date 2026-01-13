<?php
// Database Initialization Script - Create tables if not exist
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tesso_nilo_db');

// Create connection to MySQL server first (without selecting database)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Create database if not exists
$createDbSql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (!$conn->query($createDbSql)) {
    die(json_encode(['success' => false, 'message' => 'Error creating database: ' . $conn->error]));
}

// Select the database
if (!$conn->select_db(DB_NAME)) {
    die(json_encode(['success' => false, 'message' => 'Error selecting database: ' . $conn->error]));
}

// Set charset
$conn->set_charset("utf8");

// Create users table
$createUsersSql = "
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createUsersSql);

// Create berita (news) table
$createBeritaSql = "
CREATE TABLE IF NOT EXISTS berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)";
$conn->query($createBeritaSql);

// Create reservasi (booking) table
$createReservasiSql = "
CREATE TABLE IF NOT EXISTS reservasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    date_booking DATE NOT NULL,
    tickets INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
$conn->query($createReservasiSql);

// Add payment_proof column if not exists (for migration)
$checkCol = $conn->query("SHOW COLUMNS FROM reservasi LIKE 'payment_proof'");
if ($checkCol->num_rows == 0) {
    $conn->query("ALTER TABLE reservasi ADD COLUMN payment_proof VARCHAR(255) AFTER notes");
}

// Add image column to berita if not exists
$checkImgCol = $conn->query("SHOW COLUMNS FROM berita LIKE 'image'");
if ($checkImgCol->num_rows == 0) {
    $conn->query("ALTER TABLE berita ADD COLUMN image VARCHAR(255) DEFAULT 'assets/img/default-news.jpg' AFTER content");
}

// Create ticket pricing table
$createTicketPricingSql = "
CREATE TABLE IF NOT EXISTS ticket_pricing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createTicketPricingSql);

// Insert default ticket pricing
$checkTicket = $conn->query("SELECT COUNT(*) as count FROM ticket_pricing");
$ticketCount = $checkTicket->fetch_assoc()['count'];

if ($ticketCount == 0) {
    $insertTickets = "
    INSERT INTO ticket_pricing (type, price, description) VALUES
    ('Dewasa', 150000, 'Tiket masuk untuk wisatawan dewasa'),
    ('Anak-anak', 100000, 'Tiket masuk untuk anak-anak usia 5-12 tahun'),
    ('Pelajar', 125000, 'Tiket masuk untuk pelajar dengan kartu pelajar valid'),
    ('Rombongan', 125000, 'Tiket masuk untuk rombongan minimal 10 orang')
    ";
    $conn->query($insertTickets);
}

// Insert default messages if empty
$checkBerita = $conn->query("SELECT COUNT(*) as count FROM berita");
$beritaCount = $checkBerita->fetch_assoc()['count'];

if ($beritaCount == 0) {
    // Get default admin id
    $adminIdQuery = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    $adminId = 1; // Fallback
    if ($adminIdQuery->num_rows > 0) {
        $adminId = $adminIdQuery->fetch_assoc()['id'];
    }

    $insertBerita = "
    INSERT INTO berita (title, content, category, date, created_by) VALUES
    ('Laporan Konservasi Gajah', 'Populasi Gajah Sumatera terpantau stabil di zona inti. Upaya mitigasi konflik dengan masyarakat berjalan efektif.', 'Konservasi', '2025-05-10', $adminId),
    ('Studi Keragaman Flora Hutan Rawa', 'Ditemukan dua spesies Anggrek baru di area blok selatan yang memerlukan perlindungan segera.', 'Riset', '2025-04-20', $adminId),
    ('Pengembangan Jalur Ekowisata Sepeda', 'Pembukaan jalur baru sepanjang 15 km untuk wisata sepeda yang ramah lingkungan di zona penyangga. Tiket dapat dipesan melalui laman reservasi.', 'Ekowisata', '2025-06-01', $adminId)
    ";
    $conn->query($insertBerita);
}

// Insert default admin user
$checkAdmin = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
$adminCount = $checkAdmin->fetch_assoc()['count'];

if ($adminCount == 0) {
    $adminPassword = password_hash('12345', PASSWORD_BCRYPT);
    $insertAdmin = "
    INSERT INTO users (username, email, password, full_name, role, phone) 
    VALUES ('admin', 'admin@tessonilo.com', '$adminPassword', 'Administrator', 'admin', '0812345678')
    ";
    $conn->query($insertAdmin);
}

// Insert default customer user
$checkCustomer = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer' AND username = 'pengunjung'");
$customerCount = $checkCustomer->fetch_assoc()['count'];

if ($customerCount == 0) {
    $customerPassword = password_hash('12345', PASSWORD_BCRYPT);
    $insertCustomer = "
    INSERT INTO users (username, email, password, full_name, role, phone) 
    VALUES ('pengunjung', 'pengunjung@gmail.com', '$customerPassword', 'Pengunjung Setia', 'customer', '0812987654')
    ";
    $conn->query($insertCustomer);
}

echo json_encode(['success' => true, 'message' => 'Database initialized successfully']);
?>
