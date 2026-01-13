<?php
require_once '../../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendResponse(false, 'Unauthorized access', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Invalid request method', null, 405);
}

// Get total users
$userCount = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'")->fetch_assoc()['total'];

// Get total reservations
$reservasiCount = $conn->query("SELECT COUNT(*) as total FROM reservasi")->fetch_assoc()['total'];

// Get confirmed reservations
$confirmedCount = $conn->query("SELECT COUNT(*) as total FROM reservasi WHERE status = 'confirmed'")->fetch_assoc()['total'];

// Get total revenue
$revenueResult = $conn->query("SELECT SUM(total_price) as total_revenue FROM reservasi WHERE status = 'confirmed'");
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;

// Get total berita/news
$beritaCount = $conn->query("SELECT COUNT(*) as total FROM berita")->fetch_assoc()['total'];

// Get reservations by status
$statusResult = $conn->query("SELECT status, COUNT(*) as count FROM reservasi GROUP BY status");
$statusCounts = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusCounts[$row['status']] = $row['count'];
}

// Get monthly revenue
$monthlyResult = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        SUM(total_price) as revenue,
        COUNT(*) as count
    FROM reservasi 
    WHERE status = 'confirmed'
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12
");

$monthlyData = [];
while ($row = $monthlyResult->fetch_assoc()) {
    $monthlyData[] = $row;
}

$statistics = [
    'total_users' => $userCount,
    'total_reservations' => $reservasiCount,
    'confirmed_reservations' => $confirmedCount,
    'total_revenue' => floatval($totalRevenue),
    'total_news' => $beritaCount,
    'reservations_by_status' => $statusCounts,
    'monthly_data' => $monthlyData
];

sendResponse(true, 'Statistics retrieved successfully', $statistics, 200);
?>
