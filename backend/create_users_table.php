<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Create users table if it doesn't exist
    $query = "CREATE TABLE IF NOT EXISTS users (
        id VARCHAR(36) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $db->exec($query);
    
    echo json_encode([
        "success" => true,
        "message" => "Users table created/verified successfully"
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
