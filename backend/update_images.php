<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Update product image URLs to use better images
    $updates = [
        ['id' => '1cfd97ba-19be-45c2-96c5-176094525479', 'image_url' => '/images/products/ipad-new.jpg'],
        ['id' => '42a12f59-5044-4710-92a8-94952c34cfb2', 'image_url' => '/images/products/airpods-new.jpg'],
        ['id' => '6cf19a92-2a7f-4f74-b16c-962ca1f191a4', 'image_url' => '/images/products/macbook-new.jpg'],
        ['id' => '86e16034-98ef-4c3f-bc80-7eb2494fe456', 'image_url' => '/images/products/iphone14-new.jpg']
    ];
    
    $updateQuery = "UPDATE products SET image_url = :image_url WHERE id = :id";
    $stmt = $db->prepare($updateQuery);
    
    foreach ($updates as $update) {
        $stmt->execute($update);
    }
    
    echo json_encode([
        "success" => true,
        "message" => "Product images updated successfully with better visuals"
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
