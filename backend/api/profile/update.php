<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "error" => [
            "message" => "Unauthorized"
        ]
    ]);
    exit();
}

include_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (empty($data)) {
    http_response_code(400);
    echo json_encode([
        "error" => [
            "message" => "No data provided"
        ]
    ]);
    exit();
}

try {
    $db->beginTransaction();
    $updates = [];
    $params = [];

    // Handle full name update
    if (isset($data->full_name)) {
        $updates[] = "full_name = :full_name";
        $params[':full_name'] = $data->full_name;
    }

    // Handle email update
    if (isset($data->email)) {
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            throw new Exception("Email already exists");
        }

        $updates[] = "email = :email";
        $params[':email'] = $data->email;
    }

    // Handle password update
    if (isset($data->password)) {
        if (strlen($data->password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        $updates[] = "password = :password";
        $params[':password'] = password_hash($data->password, PASSWORD_DEFAULT);
    }

    if (!empty($updates)) {
        $userUpdates = [];
        $profileUpdates = [];
        $userParams = [];
        $profileParams = [];

        // Separate updates for users and profiles tables
        if (isset($data->email)) {
            $userUpdates[] = "email = :email";
            $userParams[':email'] = $data->email;
        }
        if (isset($data->password)) {
            $userUpdates[] = "password = :password";
            $userParams[':password'] = $params[':password'];
        }
        if (isset($data->full_name)) {
            $profileUpdates[] = "full_name = :full_name";
            $profileParams[':full_name'] = $data->full_name;
        }

        // Update users table if needed
        if (!empty($userUpdates)) {
            $query = "UPDATE users SET " . implode(", ", $userUpdates) . 
                     " WHERE id = :user_id";
            $userParams[':user_id'] = $_SESSION['user_id'];

            $stmt = $db->prepare($query);
            foreach ($userParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        }

        // Update profiles table if needed
        if (!empty($profileUpdates)) {
            $query = "UPDATE profiles SET " . implode(", ", $profileUpdates) . 
                     " WHERE user_id = :user_id";
            $profileParams[':user_id'] = $_SESSION['user_id'];

            $stmt = $db->prepare($query);
            foreach ($profileParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
        }

        // Get updated user data
        $query = "SELECT u.*, p.full_name, p.is_admin 
                 FROM users u 
                 LEFT JOIN profiles p ON u.id = p.user_id 
                 WHERE u.id = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Update session if email changed
        if (isset($data->email)) {
            $_SESSION['email'] = $data->email;
        }

        $db->commit();

        // Return user data in standardized format
        http_response_code(200);
        echo json_encode([
            "data" => [
                "user" => [
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "profile" => [
                        "id" => $user['id'],
                        "full_name" => $user['full_name'],
                        "is_admin" => (bool)$user['is_admin']
                    ]
                ]
            ]
        ]);
    } else {
        throw new Exception("No fields to update");
    }

} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode([
        "error" => [
            "message" => $e->getMessage()
        ]
    ]);
}
