<?php
require_once __DIR__ . '/../utils/Auth.php';

class AuthMiddleware {
    public static function validateToken() {
        $headers = apache_request_headers();
        
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "No token provided."]);
            exit();
        }

        $auth_header = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $auth_header);

        $decoded = Auth::validateToken($token);
        
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid token."]);
            exit();
        }

        return $decoded;
    }

    public static function requireAdmin() {
        $decoded = self::validateToken();
        
        if (!$decoded->is_admin) {
            http_response_code(403);
            echo json_encode(["message" => "Admin access required."]);
            exit();
        }

        return $decoded;
    }
}
