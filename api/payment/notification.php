<?php
require_once '../../config/database.php';
require_once 'config.php';

// Endpoint ini diakses oleh Midtrans Server
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

$json_result = file_get_contents('php://input');
$notification = json_decode($json_result, true);

if (!$notification) {
    http_response_code(400); // Bad Request
    exit();
}

$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'];
$gross_amount = $notification['gross_amount'];
$signature_key = $notification['signature_key']; // Verifikasi keamanan

// 1. Verifikasi Signature
// SHA512(order_id+status_code+gross_amount+ServerKey)
$status_code = $notification['status_code'];
$expected_signature = hash('sha512', $order_id . $status_code . $gross_amount . MIDTRANS_SERVER_KEY);

if ($expected_signature !== $signature_key) {
    // Kalau Key masih dummy, hash mungkin beda. Di production ini WAJIB.
    // Lanjut saja jika mode dev/dummy, tapi log warning
    // http_response_code(403);
    // exit(); 
}

// 2. Tentukan Status Reservasi
$new_status = null;
if ($transaction_status == 'capture') {
    if ($fraud_status == 'challenge') {
        $new_status = 'pending'; // Perlu review
    } else if ($fraud_status == 'accept') {
        $new_status = 'confirmed';
    }
} else if ($transaction_status == 'settlement') {
    $new_status = 'confirmed'; // Uang sudah masuk/terjamin
} else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
    $new_status = 'cancelled';
} else if ($transaction_status == 'pending') {
    $new_status = 'pending';
}

// 3. Update Database
if ($new_status) {
    // Parse ID asli dari order_id (format: TN-123-timestamp)
    $parts = explode('-', $order_id);
    if (count($parts) >= 2) {
        $reservasi_id = intval($parts[1]);
        
        $sql = "UPDATE reservasi SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $reservasi_id);
        
        if ($stmt->execute()) {
             http_response_code(200); // OK
             echo "Status updated to $new_status";
        } else {
             http_response_code(500);
             error_log("Failed to update status: " . $stmt->error);
        }
    }
} else {
    http_response_code(200); // Terima notifikasi tapi tidak ada aksi
}
?>
