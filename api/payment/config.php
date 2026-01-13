<?php
// Konfigurasi Midtrans
// Daftar di https://dashboard.midtrans.com/register untuk dapat Key

define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-XXXXXXXXXXXXXXXXX'); // Ganti dengan Server Key Sandbox Anda
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-XXXXXXXXXXXXXXXXX'); // Ganti dengan Client Key Sandbox Anda
define('MIDTRANS_IS_PRODUCTION', false); // Set true jika sudah live

// Base URL Midtrans API
define('MIDTRANS_API_URL', MIDTRANS_IS_PRODUCTION 
    ? 'https://app.midtrans.com/snap/v1/transactions' 
    : 'https://app.sandbox.midtrans.com/snap/v1/transactions');

function getMidtransHeader() {
    return [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':')
    ];
}
?>
