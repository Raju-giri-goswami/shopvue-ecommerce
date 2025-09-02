<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5176');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get query parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

try {
    $query = "SELECT * FROM products";
    $params = array();
    $whereConditions = [];
    
    if ($category) {
        $whereConditions[] = "category = :category";
        $params[':category'] = $category;
    }
    
    if ($search) {
        $whereConditions[] = "(name LIKE :search OR description LIKE :search)";
        $params[':search'] = "%{$search}%";
    }
    
    if (!empty($whereConditions)) {
        $query .= " WHERE " . implode(' AND ', $whereConditions);
    }
    
    // Get total count
    $countQuery = str_replace("SELECT *", "SELECT COUNT(*)", $query);
    $stmt = $db->prepare($countQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $totalCount = $stmt->fetchColumn();
    
    // Add pagination
    $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($query);
    
    // Bind pagination params
    $offset = ($page - 1) * $per_page;
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    // Bind other params
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode([
        "data" => $products,
        "count" => $totalCount
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
