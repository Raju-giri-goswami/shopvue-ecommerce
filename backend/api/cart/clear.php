<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    require_once '../../config/database.php';
    
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Start session for user identification
    session_start();
    
    // Get user_id from request body or session
    $user_id = null;
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['user_id'])) {
        $user_id = $input['user_id'];
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // For guest users, we can't clear without user_id
        echo json_encode(['success' => false, 'error' => 'User ID required']);
        exit;
    }
    
    // Clear all cart items for this user
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Cart cleared successfully',
        'data' => []
    ]);
    
} catch (Exception $e) {
    error_log('Cart clear error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to clear cart'
    ]);
}
?>
