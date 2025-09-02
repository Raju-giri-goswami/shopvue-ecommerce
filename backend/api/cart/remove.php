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

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->cart_item_id)) {
    try {
        // Start transaction
        $db->beginTransaction();

        // Delete the cart item
        $query = "DELETE FROM cart_items 
                 WHERE id = :cart_item_id AND user_id = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":cart_item_id", $data->cart_item_id);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new Exception("Cart item not found or unauthorized");
        }

        // Get updated cart
        $query = "SELECT ci.*, p.name, p.description, p.price, p.image_url, p.stock, 
                        p.category, p.created_at as product_created_at, 
                        p.updated_at as product_updated_at
                 FROM cart_items ci
                 LEFT JOIN products p ON ci.product_id = p.id
                 WHERE ci.user_id = :user_id";
                 
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->execute();
        
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
                    "category" => $row['category'],
                    "created_at" => $row['product_created_at'],
                    "updated_at" => $row['product_updated_at']
                ]
            ];
        }

        $db->commit();
        
        http_response_code(200);
        echo json_encode([
            "data" => $cartItems
        ]);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode([
            "error" => [
                "message" => $e->getMessage()
            ]
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "error" => [
            "message" => "Cart item ID is required"
        ]
    ]);
}
