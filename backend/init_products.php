<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Create products table if not exists
    $createTable = "CREATE TABLE IF NOT EXISTS products (
        id VARCHAR(36) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        image_url TEXT,
        stock INT NOT NULL DEFAULT 0,
        category VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($createTable);
    
    // Sample products data - only the missing ones
    $sampleProducts = [
        [
            'id' => uniqid(),
            'name' => 'iPhone 14 Pro',
            'description' => 'Latest iPhone with amazing camera features',
            'price' => 999.99,
            'image_url' => '/images/products/iphone14-new.jpg',
            'stock' => 50,
            'category' => 'smartphones'
        ],
        [
            'id' => uniqid(),
            'name' => 'MacBook Pro 16',
            'description' => 'Powerful laptop for professionals',
            'price' => 2499.99,
            'image_url' => '/images/products/macbook-new.jpg',
            'stock' => 25,
            'category' => 'laptops'
        ]
    ];
    
    // Insert sample products (always add the missing ones)
    $insertQuery = "INSERT INTO products (id, name, description, price, image_url, stock, category) 
                   VALUES (:id, :name, :description, :price, :image_url, :stock, :category)";
    
    $stmt = $db->prepare($insertQuery);
    
    foreach ($sampleProducts as $product) {
        $stmt->execute($product);
    }
    
    echo "Missing products added successfully\n";
    
} catch(PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
