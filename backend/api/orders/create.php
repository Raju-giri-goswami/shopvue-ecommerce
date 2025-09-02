<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "error" => [
            "message" => "Unauthorized"
        ]
    ]);
    exit();
}

include_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Start transaction
    $db->beginTransaction();

    // 1. Get cart items with product details
    $query = "SELECT ci.*, p.price as current_price, p.stock
              FROM cart_items ci
              LEFT JOIN products p ON ci.product_id = p.id
              WHERE ci.user_id = :user_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->execute();
    
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartItems)) {
        throw new Exception("Cart is empty");
    }

    // 2. Calculate total price and check stock
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        if ($item['quantity'] > $item['stock']) {
            throw new Exception("Not enough stock for some items");
        }
        $totalPrice += $item['current_price'] * $item['quantity'];
    }

    // 3. Create order
    $orderId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $query = "INSERT INTO orders (id, user_id, total_price, status) 
              VALUES (:id, :user_id, :total_price, 'pending')";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $orderId);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->bindParam(":total_price", $totalPrice);
    $stmt->execute();

    // 4. Create order items
    foreach ($cartItems as $item) {
        $orderItemId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // Insert order item
        $query = "INSERT INTO order_items (id, order_id, product_id, quantity, price) 
                  VALUES (:id, :order_id, :product_id, :quantity, :price)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $orderItemId);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->bindParam(":product_id", $item['product_id']);
        $stmt->bindParam(":quantity", $item['quantity']);
        $stmt->bindParam(":price", $item['current_price']);
        $stmt->execute();

        // Update product stock
        $query = "UPDATE products 
                  SET stock = stock - :quantity,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :product_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":quantity", $item['quantity']);
        $stmt->bindParam(":product_id", $item['product_id']);
        $stmt->execute();
    }

    // 5. Clear cart
    $query = "DELETE FROM cart_items WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->execute();

    // 6. Get created order
    $query = "SELECT * FROM orders WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $orderId);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Commit transaction
    $db->commit();
    
    http_response_code(201);
    echo json_encode([
        "data" => $order
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
