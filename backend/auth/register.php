<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5174');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name, email and password are required']);
        exit;
    }
    
    $name = trim($input['name']);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $password = $input['password'];
    
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name cannot be empty']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }
    
    if (strlen($password) < 6) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 6 characters long']);
        exit;
    }
    
    $database = new Database();
    $pdo = $database->getConnection();
    
    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'User with this email already exists']);
        exit;
    }
    
    // Create new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userId = bin2hex(random_bytes(16)); // Generate UUID-like ID
    
    $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
    $stmt->execute([$userId, $name, $email, $hashedPassword]);
    
    echo json_encode([
        'message' => 'User registered successfully',
        'user' => [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user'
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
