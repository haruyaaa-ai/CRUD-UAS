<?php
require_once '../../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendResponse(false, 'Unauthorized access', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$format = isset($_GET['format']) ? $_GET['format'] : 'csv';
$type = isset($_GET['type']) ? $_GET['type'] : 'reservasi';

// Get data
if ($type === 'reservasi') {
    $result = $conn->query("SELECT id, name, email, phone, citizen_type, date_booking, tickets, total_price, status, created_at FROM reservasi ORDER BY created_at DESC");
    $filename = 'reservasi_report_' . date('Y-m-d_His');
} else if ($type === 'berita') {
    $result = $conn->query("SELECT id, title, category, date, content, created_at FROM berita ORDER BY date DESC");
    $filename = 'berita_report_' . date('Y-m-d_His');
} else {
    sendResponse(false, 'Invalid type', null, 400);
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

if (empty($data)) {
    sendResponse(false, 'No data to export', null, 400);
}

if ($format === 'csv' || $format === 'excel') {
    // Excel-compatible CSV with UTF-8 BOM
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    
    // BOM for UTF-8 (makes Excel recognize UTF-8 encoding)
    echo "\xEF\xBB\xBF";
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Write headers with proper formatting
    $headers = array_keys($data[0]);
    $formattedHeaders = array_map(function($header) {
        return ucwords(str_replace('_', ' ', $header));
    }, $headers);
    fputcsv($output, $formattedHeaders);
    
    // Write data with formatting
    foreach ($data as $row) {
        $formattedRow = [];
        foreach ($row as $key => $value) {
            // Format specific columns for Excel
            if ($key === 'total_price') {
                $formattedRow[] = 'Rp ' . number_format($value, 0, ',', '.');
            } elseif ($key === 'date_booking' || $key === 'date' || $key === 'created_at') {
                $formattedRow[] = date('d/m/Y H:i', strtotime($value));
            } else {
                $formattedRow[] = $value;
            }
        }
        fputcsv($output, $formattedRow);
    }
    
    fclose($output);
    exit();
    
} else if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.json"');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
    
} else if ($format === 'pdf') {
    // Include PDF export script directly
    require_once 'export_pdf_mpdf.php';
    exit();
    
} else {
    sendResponse(false, 'Unsupported format', null, 400);
}
?>
