<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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
    // Check if user is admin
    $query = "SELECT is_admin FROM profiles WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->execute();
    $isAdmin = $stmt->fetchColumn();

    // Get orders with order items, product details, and user info
    $query = "SELECT o.*, 
                     oi.id as item_id, 
                     oi.product_id,
                     oi.quantity,
                     oi.price as item_price,
                     oi.created_at as item_created_at,
                     p.name as product_name,
                     p.image_url as product_image_url,
                     pr.full_name as customer_name
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              LEFT JOIN products p ON oi.product_id = p.id
              LEFT JOIN profiles pr ON o.user_id = pr.user_id
              " . (!$isAdmin ? "WHERE o.user_id = :user_id" : "") . "
              ORDER BY o.created_at DESC";
    
    $stmt = $db->prepare($query);
    if (!$isAdmin) {
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
    }
    $stmt->execute();
    
    $orders = [];
    $currentOrder = null;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!$currentOrder || $currentOrder['id'] !== $row['id']) {
            if ($currentOrder) {
                $orders[] = $currentOrder;
            }
            $currentOrder = [
                "id" => $row['id'],
                "user_id" => $row['user_id'],
                "total_price" => (float)$row['total_price'],
                "status" => $row['status'],
                "created_at" => $row['created_at'],
                "updated_at" => $row['updated_at'],
                "customer_name" => $row['customer_name'],
                "order_items" => []
            ];
        }
        
        if ($row['item_id']) {
            $currentOrder['order_items'][] = [
                "id" => $row['item_id'],
                "order_id" => $row['id'],
                "product_id" => $row['product_id'],
                "quantity" => (int)$row['quantity'],
                "price" => (float)$row['item_price'],
                "created_at" => $row['item_created_at'],
                "product" => [
                    "name" => $row['product_name'],
                    "image_url" => $row['product_image_url']
                ]
            ];
        }
    }
    
    if ($currentOrder) {
        $orders[] = $currentOrder;
    }

    http_response_code(200);
    echo json_encode([
        "data" => $orders
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
