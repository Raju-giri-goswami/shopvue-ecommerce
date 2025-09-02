<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Check if role column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    $roleExists = $stmt->fetch() !== false;
    
    if (!$roleExists) {
        // Add role column
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user' AFTER email");
        
        // Update admin user to have admin role
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = 'admin@admin.com'");
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Role column added successfully',
            'admin_updated' => $stmt->rowCount() > 0
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Role column already exists'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
