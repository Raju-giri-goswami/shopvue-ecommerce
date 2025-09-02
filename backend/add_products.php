<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Additional products to add
    $newProducts = [
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Apple Watch Series 8',
            'description' => 'Advanced health and fitness tracking smartwatch with GPS',
            'price' => 399.99,
            'image_url' => '/images/products/apple-watch.jpg',
            'stock' => 40,
            'category' => 'accessories'
        ],
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Samsung Galaxy S23',
            'description' => 'Flagship Android smartphone with excellent camera system',
            'price' => 799.99,
            'image_url' => '/images/products/samsung-phone.jpg',
            'stock' => 35,
            'category' => 'smartphones'
        ],
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Nintendo Switch OLED',
            'description' => 'Portable gaming console with vibrant OLED display',
            'price' => 349.99,
            'image_url' => '/images/products/nintendo-switch.jpg',
            'stock' => 25,
            'category' => 'gaming'
        ],
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Sony WH-1000XM4',
            'description' => 'Premium noise-cancelling wireless headphones',
            'price' => 349.99,
            'image_url' => '/images/products/sony-headphones.jpg',
            'stock' => 30,
            'category' => 'accessories'
        ],
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Dell XPS 13',
            'description' => 'Ultra-portable laptop perfect for productivity',
            'price' => 1299.99,
            'image_url' => '/images/products/dell-laptop.jpg',
            'stock' => 20,
            'category' => 'laptops'
        ],
        [
            'id' => bin2hex(random_bytes(16)),
            'name' => 'Samsung Galaxy Tab S8',
            'description' => 'Powerful Android tablet for work and entertainment',
            'price' => 699.99,
            'image_url' => '/images/products/samsung-tablet.jpg',
            'stock' => 15,
            'category' => 'tablets'
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO products (id, name, description, price, image_url, stock, category, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    $addedCount = 0;
    foreach ($newProducts as $product) {
        // Check if product with similar name already exists
        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
        $checkStmt->execute([$product['name']]);
        
        if (!$checkStmt->fetch()) {
            $stmt->execute([
                $product['id'],
                $product['name'],
                $product['description'],
                $product['price'],
                $product['image_url'],
                $product['stock'],
                $product['category']
            ]);
            $addedCount++;
        }
    }
    
    // Get total product count
    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $totalProducts = $countStmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => "$addedCount new products added successfully",
        'total_products' => $totalProducts,
        'added_products' => $addedCount
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
