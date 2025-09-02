<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Create users table if it doesn't exist
    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $db->exec($createUsersTable);
    
    // Create cart_items table if it doesn't exist
    $createCartItemsTable = "CREATE TABLE IF NOT EXISTS cart_items (
        id VARCHAR(36) PRIMARY KEY,
        user_id VARCHAR(100) NOT NULL,
        product_id VARCHAR(36) NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_product_id (product_id)
    )";
    $db->exec($createCartItemsTable);
    
    // Check if admin user exists, if not create one
    $checkAdmin = "SELECT id FROM users WHERE email = 'admin@example.com'";
    $stmt = $db->prepare($checkAdmin);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insertAdmin = "INSERT INTO users (name, email, password) VALUES ('Admin User', 'admin@example.com', ?)";
        $stmt = $db->prepare($insertAdmin);
        $stmt->execute([$adminPassword]);
    }
    
    // Check if products table exists
    $query = "SHOW TABLES LIKE 'products'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Count products
        $query = "SELECT COUNT(*) as count FROM products";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $productsCount = $row['count'];
    } else {
        $productsCount = 0;
    }
    
    // Count users
    $usersQuery = "SELECT COUNT(*) as count FROM users";
    $usersStmt = $db->prepare($usersQuery);
    $usersStmt->execute();
    $usersRow = $usersStmt->fetch(PDO::FETCH_ASSOC);
    $usersCount = $usersRow['count'];
    
    echo json_encode([
        "success" => true,
        "message" => "Database connection successful",
        "products_count" => $productsCount,
        "users_count" => $usersCount,
        "database_name" => "shopvue",
        "admin_note" => "Default admin: admin@example.com / admin123"
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
