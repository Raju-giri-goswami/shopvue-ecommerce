<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    try {
        // Check if user exists
        $query = "SELECT id, name, email, password FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $data->email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($data->password, $user['password'])) {
                // Create user response
                $userResponse = [
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "profile" => [
                        "id" => $user['id'],
                        "user_id" => $user['id'],
                        "full_name" => $user['name'],
                        "is_admin" => $user['email'] === 'admin@example.com' // Simple admin check
                    ]
                ];
                
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful",
                    "user" => $userResponse
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "User not found"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Database Error: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required"
    ]);
}
