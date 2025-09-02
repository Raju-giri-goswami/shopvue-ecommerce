<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(401);
    echo json_encode([
        "error" => [
            "message" => "Unauthorized - Admin access required"
        ]
    ]);
    exit();
}

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->order_id) || !isset($data->status)) {
    http_response_code(400);
    echo json_encode([
        "error" => [
            "message" => "Order ID and status are required"
        ]
    ]);
    exit();
}

try {
    // Update order status
    $query = "UPDATE orders 
              SET status = :status,
                  updated_at = CURRENT_TIMESTAMP
              WHERE id = :order_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":status", $data->status);
    $stmt->bindParam(":order_id", $data->order_id);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception("Order not found");
    }

    // Get updated order
    $query = "SELECT * FROM orders WHERE id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":order_id", $data->order_id);
    $stmt->execute();
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "data" => $order
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
