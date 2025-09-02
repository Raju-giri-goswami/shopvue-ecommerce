<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    require_once '../../config/database.php';
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data || !isset($data['id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Item ID and quantity are required']);
        exit;
    }

    $quantity = (int)$data['quantity'];
    if ($quantity <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Quantity must be greater than 0']);
        exit;
    }

    session_start();
    $user_id = isset($data['user_id']) && !empty($data['user_id']) ? $data['user_id'] : 'guest_' . session_id();

    // Update cart item
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
    $result = $stmt->execute([$quantity, $data['id'], $user_id]);

    if ($result) {
        // Get updated cart items
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

        echo json_encode([
            "success" => true,
            "message" => "Cart updated successfully",
            "data" => $cartItems
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Cart item not found']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update cart: " . $e->getMessage()]);
}
?>
