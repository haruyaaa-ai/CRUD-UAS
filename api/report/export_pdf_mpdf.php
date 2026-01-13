<?php
require_once '../../config/database.php';
require_once '../../vendor/autoload.php';

if (!class_exists('\Mpdf\Mpdf')) {
    sendResponse(false, 'Library mPDF tidak ditemukan. Silakan jalankan composer install atau periksa folder vendor.', null, 500);
}

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    sendResponse(false, 'Unauthorized access', null, 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Invalid request method', null, 405);
}

$type = isset($_GET['type']) ? $_GET['type'] : 'reservasi';

// Get data
if ($type === 'reservasi') {
    $result = $conn->query("SELECT id, name, email, phone, citizen_type, date_booking, tickets, total_price, status, created_at FROM reservasi ORDER BY created_at DESC");
    $title = 'Laporan Reservasi';
    $filename = 'reservasi_report_' . date('Y-m-d_His') . '.pdf';
} else if ($type === 'berita') {
    $result = $conn->query("SELECT id, title, category, date, content, created_at FROM berita ORDER BY date DESC");
    $title = 'Laporan Berita & Informasi';
    $filename = 'berita_report_' . date('Y-m-d_His') . '.pdf';
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

    // Increase limits for PDF generation
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', '120');

    try {
        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 15,
        'margin_bottom' => 15,
        'tempDir' => __DIR__ . '/tmp'
    ]);

    // Set document properties
    $mpdf->debug = true;
    $mpdf->showImageErrors = true;
    $mpdf->SetTitle($title);
    $mpdf->SetAuthor('Tesso Nilo System');
    $mpdf->SetCreator('Tesso Nilo Management System');

    // Generate HTML for PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { 
                font-family: "DejaVu Sans", Arial, sans-serif; 
                font-size: 10pt;
            }
            .header { 
                text-align: center; 
                margin-bottom: 20px; 
                border-bottom: 3px solid #1b742e;
                padding-bottom: 10px;
            }
            .header h1 { 
                margin: 0; 
                color: #1b742e; 
                font-size: 18pt;
            }
            .header p { 
                margin: 5px 0; 
                color: #666; 
                font-size: 10pt;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 15px; 
            }
            th { 
                background-color: #1b742e; 
                color: white; 
                padding: 8px 5px; 
                text-align: left; 
                border: 1px solid #0d4d1a;
                font-size: 9pt;
                font-weight: bold;
            }
            td { 
                padding: 6px 5px; 
                border: 1px solid #ddd; 
                font-size: 8pt;
                vertical-align: top;
            }
            tr:nth-child(even) { 
                background-color: #f9f9f9; 
            }
            .footer { 
                text-align: center; 
                margin-top: 20px; 
                font-size: 8pt; 
                color: #666; 
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }
            .status-confirmed { 
                color: #0d9488; 
                font-weight: bold; 
            }
            .status-pending { 
                color: #f59e0b; 
                font-weight: bold; 
            }
            .status-cancelled { 
                color: #dc2626; 
                font-weight: bold; 
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>' . htmlspecialchars($title) . '</h1>
            <p><strong>Taman Nasional Tesso Nilo</strong></p>
            <p>Tanggal Cetak: ' . date('d F Y, H:i:s') . ' WIB</p>
            <p>Total Data: ' . count($data) . ' record</p>
        </div>
        
        <table>
            <thead>
                <tr>';

    // Add table headers
    $headers = array_keys($data[0]);
    foreach ($headers as $header) {
        $headerLabel = ucwords(str_replace('_', ' ', $header));
        $html .= '<th>' . htmlspecialchars($headerLabel) . '</th>';
    }

    $html .= '
                </tr>
            </thead>
            <tbody>';

    // Add table data
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $key => $value) {
            // Format specific columns
            if ($key === 'total_price') {
                $value = 'Rp ' . number_format($value, 0, ',', '.');
            } elseif ($key === 'date_booking' || $key === 'date' || $key === 'created_at') {
                $value = date('d/m/Y H:i', strtotime($value));
            } elseif ($key === 'status') {
                $statusClass = 'status-' . strtolower($value);
                $html .= '<td class="' . $statusClass . '">' . htmlspecialchars($value) . '</td>';
                continue;
            } elseif ($key === 'content' && strlen($value) > 100) {
                $value = substr($value, 0, 100) . '...';
            }
            
            $html .= '<td>' . htmlspecialchars($value) . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '
            </tbody>
        </table>
        
        <div class="footer">
            <p><strong>Taman Nasional Tesso Nilo - Management System</strong></p>
            <p>Dokumen ini digenerate secara otomatis oleh sistem</p>
        </div>
    </body>
    </html>';

    // Clear any previous output buffers
    if (ob_get_length()) ob_clean();

    // Explicitly set headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Output PDF
    $mpdf->Output($filename, 'I'); // 'I' = Inline (open in browser)
    exit();

} catch (\Mpdf\MpdfException $e) {
    error_log('mPDF Error: ' . $e->getMessage());
    sendResponse(false, 'Error generating PDF: ' . $e->getMessage(), null, 500);
}
?>
