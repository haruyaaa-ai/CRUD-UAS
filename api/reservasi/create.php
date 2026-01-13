<?php
require_once '../../config/database.php';
require_once '../payment/config.php'; // Include Midtrans Config

// Check if user is logged in
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', null, 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, 405);
}

// Handle POST JSON (Preferred) or FormData
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
$data = [];
if (strpos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
} else {
    $data = $_POST;
}

// Validate required fields
$required = ['name', 'email', 'phone', 'date_booking', 'tickets'];
foreach ($required as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        sendResponse(false, ucfirst($field) . ' is required', null, 400);
    }
}

// User Data
$user_id = $_SESSION['user_id'];
$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$phone = $conn->real_escape_string($data['phone']);
$date_booking = $conn->real_escape_string($data['date_booking']);
$tickets = intval($data['tickets']);
$notes = isset($data['notes']) ? $conn->real_escape_string($data['notes']) : '';

// Calculate Price
$citizen_type = isset($data['citizen_type']) ? $data['citizen_type'] : 'wni';
$price_per_ticket = ($citizen_type === 'wna') ? 150000 : 15000;
$total_price = $tickets * $price_per_ticket;

// 1. Insert Reservation to DB (Status 'pending')
$sql = "INSERT INTO reservasi (user_id, name, email, phone, date_booking, tickets, citizen_type, total_price, notes, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssisds", $user_id, $name, $email, $phone, $date_booking, $tickets, $citizen_type, $total_price, $notes);

if ($stmt->execute()) {
    $reservasi_id = $stmt->insert_id;
    $order_id = 'TN-' . $reservasi_id . '-' . time(); // Unique Order ID for Payment Gateway

    // 2. Request Snap Token from Midtrans
    $token = null;
    $redirect_url = null;

    // Check if Keys are configured (Basic check)
    $is_configured = strpos(MIDTRANS_SERVER_KEY, 'XXXXXXXX') === false;

    if ($is_configured) {
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $total_price
        ];

        $customer_details = [
            'first_name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        $item_details = [
            [
                'id' => 'TICKET-TN-' . strtoupper($citizen_type),
                'price' => $price_per_ticket,
                'quantity' => $tickets,
                'name' => "Tiket Masuk Tesso Nilo (" . strtoupper($citizen_type) . ")"
            ]
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            // 'enabled_payments' => ['gopay', 'shopeepay', 'qris'], // Optional: Limit methods
        ];

        // cURL Request to Midtrans
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, MIDTRANS_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, getMidtransHeader());
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 201) {
            $midtransData = json_decode($result, true);
            $token = $midtransData['token'];
            $redirect_url = $midtransData['redirect_url'];
        } else {
            // Log Error but don't fail booking entirely? 
            // Better to fail specific payment step.
            // For now, return booking success but no token (will need retry)
        }
    } else {
        // Fallback for Demo without Real API Keys
        $token = 'dummy_token_' . time();
        $redirect_url = '#'; 
    }

    $response = [
        'id' => $reservasi_id,
        'order_id' => $order_id,
        'name' => $name,
        'tickets' => $tickets,
        'total_price' => $total_price,
        'status' => 'pending',
        'snap_token' => $token, // Token for Frontend Snap JS
        'payment_url' => $redirect_url,
        'is_simulation' => !$is_configured
    ];
    
    sendResponse(true, 'Booking created. Proceed to payment.', $response, 201);

} else {
    sendResponse(false, 'Failed to create reservation: ' . $stmt->error, null, 500);
}
?>
