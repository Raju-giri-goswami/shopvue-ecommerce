<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Get ID from URL
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

try {
    $product_item = $product->getOne();
    
    if ($product_item) {
        http_response_code(200);
        echo json_encode($product_item);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product not found."));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Error: " . $e->getMessage()));
}
