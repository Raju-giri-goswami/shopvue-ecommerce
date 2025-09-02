<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode([
        "error" => "Missing email or password"
    ]);
    exit();
}

if (!$data || !isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields."]);
    exit();
}

try {
    // Check user credentials
    $query = "SELECT u.*, p.full_name, p.is_admin 
              FROM users u
              LEFT JOIN profiles p ON u.id = p.user_id
              WHERE u.email = :email";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($data->password, $row['password'])) {
            // Start session
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = $row['is_admin'];
            
            http_response_code(200);
            echo json_encode([
                "data" => [
                    "user" => [
                        "id" => $row['id'],
                        "email" => $row['email'],
                        "profile" => [
                            "id" => $row['id'],
                            "full_name" => $row['full_name'],
                            "is_admin" => (bool)$row['is_admin']
                        ]
                    ]
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "error" => [
                    "message" => "Invalid login credentials"
                ]
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            "error" => [
                "message" => "Invalid login credentials"
            ]
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
