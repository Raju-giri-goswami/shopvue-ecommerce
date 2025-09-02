<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5174');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Set session cookie parameters for cross-origin requests
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,
    'httponly' => false,
    'samesite' => 'Lax'
]);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once '../../config/database.php';
    
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    session_start();
    $user_id = isset($_GET['user_id']) && !empty($_GET['user_id']) ? $_GET['user_id'] : 'guest_' . session_id();

    $stmt = $pdo->prepare("
        SELECT ci.*, p.name, p.description, p.price, p.image_url, p.stock, p.category
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
        ORDER BY ci.created_at DESC
    ");
    $stmt->execute([$user_id]);
    
    $cartItems = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cartItems[] = [
            "id" => $row['id'],
            "user_id" => $row['user_id'],
            "product_id" => $row['product_id'],
            "quantity" => (int)$row['quantity'],
            "created_at" => $row['created_at'],
            "updated_at" => $row['updated_at'],
            "product" => [
                "id" => $row['product_id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "price" => (float)$row['price'],
                "image_url" => $row['image_url'],
                "stock" => (int)$row['stock'],
                "category" => $row['category']
            ]
        ];
    }
    
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "data" => $cartItems,
        "user_id" => $user_id
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Failed to fetch cart items: " . $e->getMessage()
    ]);
}
?>
