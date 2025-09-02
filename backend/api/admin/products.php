<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

// Check if user is logged in and is admin
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

    if (!$isAdmin) {
        throw new Exception("Admin access required");
    }

    // Get request method and data
    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"));

    switch ($method) {
        case 'POST':
            // Create new product
            if (!isset($data->name) || !isset($data->price) || !isset($data->category)) {
                throw new Exception("Missing required fields");
            }

            $productId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );

            $query = "INSERT INTO products (id, name, description, price, image_url, stock, category)
                     VALUES (:id, :name, :description, :price, :image_url, :stock, :category)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $productId);
            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":description", $data->description);
            $stmt->bindParam(":price", $data->price);
            $stmt->bindParam(":image_url", $data->image_url);
            $stmt->bindParam(":stock", $data->stock);
            $stmt->bindParam(":category", $data->category);
            $stmt->execute();

            // Get created product
            $query = "SELECT * FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $productId);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            http_response_code(201);
            echo json_encode(["data" => $product]);
            break;

        case 'PUT':
            // Update existing product
            if (!isset($data->id)) {
                throw new Exception("Product ID is required");
            }

            $updates = [];
            $params = [];

            if (isset($data->name)) {
                $updates[] = "name = :name";
                $params[':name'] = $data->name;
            }
            if (isset($data->description)) {
                $updates[] = "description = :description";
                $params[':description'] = $data->description;
            }
            if (isset($data->price)) {
                $updates[] = "price = :price";
                $params[':price'] = $data->price;
            }
            if (isset($data->image_url)) {
                $updates[] = "image_url = :image_url";
                $params[':image_url'] = $data->image_url;
            }
            if (isset($data->stock)) {
                $updates[] = "stock = :stock";
                $params[':stock'] = $data->stock;
            }
            if (isset($data->category)) {
                $updates[] = "category = :category";
                $params[':category'] = $data->category;
            }

            if (!empty($updates)) {
                $query = "UPDATE products SET " . implode(", ", $updates) . 
                         ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
                $params[':id'] = $data->id;

                $stmt = $db->prepare($query);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->execute();

                // Get updated product
                $query = "SELECT * FROM products WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":id", $data->id);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                http_response_code(200);
                echo json_encode(["data" => $product]);
            } else {
                throw new Exception("No fields to update");
            }
            break;

        case 'DELETE':
            // Delete product
            if (!isset($data->id)) {
                throw new Exception("Product ID is required");
            }

            // Check if product is in any orders
            $query = "SELECT COUNT(*) FROM order_items WHERE product_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $data->id);
            $stmt->execute();
            $hasOrders = $stmt->fetchColumn() > 0;

            if ($hasOrders) {
                throw new Exception("Cannot delete product that has orders");
            }

            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $data->id);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception("Product not found");
            }

            http_response_code(200);
            echo json_encode([
                "data" => [
                    "message" => "Product deleted successfully"
                ]
            ]);
            break;

        default:
            throw new Exception("Method not allowed");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
