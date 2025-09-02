<?php
header('Content-Type: text/plain');
require_once '../config/database.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if ($pdo) {
        echo "Database connection: SUCCESS\n";
        
        // Check if cart_items table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'cart_items'");
        if ($stmt->rowCount() > 0) {
            echo "cart_items table: EXISTS\n";
            
            // Show table structure
            $stmt = $pdo->query("DESCRIBE cart_items");
            echo "\nTable structure:\n";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo $row['Field'] . " - " . $row['Type'] . "\n";
            }
        } else {
            echo "cart_items table: NOT EXISTS\n";
        }
        
        // Check if products table exists and has data
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nProducts count: " . $count['count'] . "\n";
        
    } else {
        echo "Database connection: FAILED\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
