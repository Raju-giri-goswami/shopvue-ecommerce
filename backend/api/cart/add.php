<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    require_once '../../config/database.php';
    $database = new Database();
    $pdo = $database->getConnection();

    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
        exit;
    }

    // Start session
    session_start();

    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Product ID and quantity are required']);
        exit;
    }

    $quantity = (int)$data['quantity'];
    if ($quantity <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Quantity must be greater than 0']);
        exit;
    }

    $user_id = isset($data['user_id']) && !empty($data['user_id']) ? $data['user_id'] : 'guest_' . session_id();
    // Verify product exists and has sufficient stock
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
    $stmt->execute([$data['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }

    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Insufficient stock']);
        exit;
    }

    // Check if item already exists in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $data['product_id']]);
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // Update existing cart item
        $newQuantity = $existingItem['quantity'] + $quantity;
        if ($newQuantity > $product['stock']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Cannot add more items. Insufficient stock available.']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Insert new cart item
        $cart_item_id = uniqid('cart_', true);
        $stmt = $pdo->prepare("INSERT INTO cart_items (id, user_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cart_item_id, $user_id, $data['product_id'], $quantity]);
    }

    // Get updated cart items for this user
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

    http_response_code(201);
    echo json_encode([
        "success" => true,
        "message" => "Item added to cart successfully",
        "data" => $cartItems,
        "user_id" => $user_id
    ]);

} catch (Exception $e) {
    error_log("Error adding item to cart: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Failed to add item to cart: " . $e->getMessage()]);
}
?>
