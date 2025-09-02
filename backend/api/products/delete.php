<?php
require_once '../../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5176');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Get product ID from request body
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = $input['id'] ?? null;
    
    if (empty($productId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Product ID is required']);
        exit;
    }
    
    // Check if product exists
    $stmt = $pdo->prepare("SELECT id, name FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }
    
    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully',
            'product' => $product
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete product']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
