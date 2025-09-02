<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = 'admin@admin.com'");
    $stmt->execute();
    $adminUser = $stmt->fetch();
    
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    if ($adminUser) {
        // Update existing admin user
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = 'admin@admin.com'");
        $stmt->execute([$hashedPassword]);
        $message = 'Admin user updated successfully';
    } else {
        // Create new admin user
        $adminId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password, role, created_at) VALUES (?, 'Admin User', 'admin@admin.com', ?, 'admin', NOW())");
        $stmt->execute([$adminId, $hashedPassword]);
        $message = 'Admin user created successfully';
    }
    
    // Get user count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'users_count' => $userCount
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
