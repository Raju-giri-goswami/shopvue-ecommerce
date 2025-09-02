<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5176');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once '../../config/database.php';
    $database = new Database();
    $pdo = $database->getConnection();
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connection successful'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
