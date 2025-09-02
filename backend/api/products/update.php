<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

// Verify admin access
$user = AuthMiddleware::requireAdmin();

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $product->id = $data->id;
    $product->name = $data->name ?? "";
    $product->description = $data->description ?? "";
    $product->price = $data->price ?? 0;
    $product->image_url = $data->image_url ?? "";
    $product->stock = $data->stock ?? 0;
    $product->category = $data->category ?? "";

    try {
        if ($product->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Product updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update product."));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Error: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update product. Missing ID."));
}
