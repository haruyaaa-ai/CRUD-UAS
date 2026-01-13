<?php
require_once '../../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Invalid request method', null, 405);
}

// Get single reservasi by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['role'] === 'admin';
    
    // Admin can see all, customer can only see their own
    if ($is_admin) {
        $sql = "SELECT * FROM reservasi WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        $sql = "SELECT * FROM reservasi WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendResponse(false, 'Reservasi not found', null, 404);
    }
    
    $reservasi = $result->fetch_assoc();
    sendResponse(true, 'Reservasi retrieved successfully', $reservasi, 200);
} else {
    // Get all reservasi with pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    
    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['role'] === 'admin';
    
    if ($is_admin) {
        // Admin gets all reservations
        $countResult = $conn->query("SELECT COUNT(*) as total FROM reservasi");
        $sql = "SELECT * FROM reservasi ORDER BY created_at DESC LIMIT ? OFFSET ?";
    } else {
        // Customer gets only their own
        $countResult = $conn->query("SELECT COUNT(*) as total FROM reservasi WHERE user_id = $user_id");
        $sql = "SELECT * FROM reservasi WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
    }
    
    $countRow = $countResult->fetch_assoc();
    $total = $countRow['total'];
    
    $stmt = $conn->prepare($sql);
    if ($is_admin) {
        $stmt->bind_param("ii", $limit, $offset);
    } else {
        $stmt->bind_param("iii", $user_id, $limit, $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reservasiList = [];
    while ($row = $result->fetch_assoc()) {
        $reservasiList[] = $row;
    }
    
    $response = [
        'data' => $reservasiList,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ];
    
    sendResponse(true, 'Reservasi list retrieved successfully', $response, 200);
}
?>
