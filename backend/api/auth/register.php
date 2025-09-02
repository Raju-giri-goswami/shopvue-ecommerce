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

if (
    !empty($data->fullName) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    try {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = :email";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bindParam(":email", $data->email);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Email already exists"
            ]);
            exit;
        }
        
        // Hash the password
        $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
        
        // Generate UUID for user ID
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // Prepare insert query
        $query = "INSERT INTO users (id, name, email, password, created_at) 
                 VALUES (:id, :name, :email, :password, NOW())";
        
        $stmt = $db->prepare($query);
        
        // Bind values
        $stmt->bindParam(":id", $uuid);
        $stmt->bindParam(":name", $data->fullName);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":password", $hashed_password);
        
        // Execute query
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "User registered successfully",
                "userId" => $db->lastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Unable to register user"
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
        "message" => "Unable to register. Data is incomplete."
    ]);
}
