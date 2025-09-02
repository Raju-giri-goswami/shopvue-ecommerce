<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

if (
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->stock)
) {
    $product->name = $data->name;
    $product->description = $data->description ?? "";
    $product->price = $data->price;
    $product->image_url = $data->image_url ?? "";
    $product->stock = $data->stock;
    $product->category = $data->category ?? "";

    try {
        if ($product->create()) {
            http_response_code(201);
            echo json_encode(array(
                "message" => "Product created successfully.",
                "id" => $product->id
            ));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create product."));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Error: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
