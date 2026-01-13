<?php
require_once '../../config/database.php';
require_once '../../vendor/autoload.php';

header('Content-Type: application/json');

try {
    if (!class_exists('\Mpdf\Mpdf')) {
        echo json_encode(['success' => false, 'message' => 'mPDF Class not found']);
        exit;
    }

    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . '/tmp'
    ]);
    
    $mpdf->WriteHTML('<h1>Test Success</h1>');
    $output = $mpdf->Output('', 'S'); // String output
    
    echo json_encode([
        'success' => true, 
        'message' => 'mPDF is working locally',
        'length' => strlen($output)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
} catch (Error $e) {
     echo json_encode([
        'success' => false, 
        'message' => 'Fatal Error: ' . $e->getMessage()
    ]);
}
