<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/Auth.php';

// Sample product data for migration
$products = [
    [
        'id' => Auth::generateUUID(),
        'name' => 'iPhone 14 Pro',
        'description' => 'Latest iPhone with amazing camera features',
        'price' => 999.99,
        'image_url' => 'https://example.com/iphone14.jpg',
        'stock' => 50,
        'category' => 'smartphones'
    ],
    [
        'id' => Auth::generateUUID(),
        'name' => 'MacBook Pro 16',
        'description' => 'Powerful laptop for professionals',
        'price' => 2499.99,
        'image_url' => 'https://example.com/macbook.jpg',
        'stock' => 25,
        'category' => 'laptops'
    ],
    [
        'id' => Auth::generateUUID(),
        'name' => 'AirPods Pro',
        'description' => 'Wireless earbuds with noise cancellation',
        'price' => 249.99,
        'image_url' => 'https://example.com/airpods.jpg',
        'stock' => 100,
        'category' => 'accessories'
    ],
    [
        'id' => Auth::generateUUID(),
        'name' => 'iPad Pro',
        'description' => 'Powerful tablet for creativity',
        'price' => 799.99,
        'image_url' => 'https://example.com/ipad.jpg',
        'stock' => 30,
        'category' => 'tablets'
    ]
];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Begin transaction
    $db->beginTransaction();
    
    // Disable foreign key checks temporarily
    $db->exec('SET FOREIGN_KEY_CHECKS=0');
    
    // Clear existing products
    $clear_query = "DELETE FROM cart_items";
    $db->exec($clear_query);
    
    $clear_query = "DELETE FROM order_items";
    $db->exec($clear_query);
    
    $clear_query = "DELETE FROM products";
    $db->exec($clear_query);
    
    // Re-enable foreign key checks
    $db->exec('SET FOREIGN_KEY_CHECKS=1');
    
    // Insert products
    $query = "INSERT INTO products (id, name, description, price, image_url, stock, category) 
              VALUES (:id, :name, :description, :price, :image_url, :stock, :category)";
    
    $stmt = $db->prepare($query);
    $inserted = 0;
    
    foreach ($products as $product) {
        $stmt->bindParam(':id', $product['id']);
        $stmt->bindParam(':name', $product['name']);
        $stmt->bindParam(':description', $product['description']);
        $stmt->bindParam(':price', $product['price']);
        $stmt->bindParam(':image_url', $product['image_url']);
        $stmt->bindParam(':stock', $product['stock']);
        $stmt->bindParam(':category', $product['category']);
        
        if ($stmt->execute()) {
            $inserted++;
        }
    }
    
    // Re-enable foreign key checks if there was an error
    $db->exec('SET FOREIGN_KEY_CHECKS=1');
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        "message" => "Products migration completed successfully",
        "products_inserted" => $inserted
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($db->inTransaction()) {
        // Make sure foreign key checks are re-enabled even on error
        $db->exec('SET FOREIGN_KEY_CHECKS=1');
        $db->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        "message" => "Error during migration: " . $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}
