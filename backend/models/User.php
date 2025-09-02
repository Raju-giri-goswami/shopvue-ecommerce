<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";
    private $profile_table = "profiles";

    public $id;
    public $email;
    public $password;
    public $full_name;
    public $is_admin;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            $this->conn->beginTransaction();

            // Create user
            $query = "INSERT INTO " . $this->table_name . " (id, email, password) VALUES (:id, :email, :password)";
            $stmt = $this->conn->prepare($query);

            $this->id = Auth::generateUUID();
            $hashed_password = Auth::hashPassword($this->password);

            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $hashed_password);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            // Create profile
            $query = "INSERT INTO " . $this->profile_table . " (id, user_id, full_name, is_admin) VALUES (:id, :user_id, :full_name, :is_admin)";
            $stmt = $this->conn->prepare($query);

            $profile_id = Auth::generateUUID();
            $is_admin = false;

            $stmt->bindParam(":id", $profile_id);
            $stmt->bindParam(":user_id", $this->id);
            $stmt->bindParam(":full_name", $this->full_name);
            $stmt->bindParam(":is_admin", $is_admin);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function login() {
        $query = "SELECT u.*, p.full_name, p.is_admin 
                 FROM " . $this->table_name . " u
                 LEFT JOIN " . $this->profile_table . " p ON u.id = p.user_id
                 WHERE u.email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && Auth::verifyPassword($this->password, $row['password'])) {
            return [
                'token' => Auth::generateToken($row['id'], $row['is_admin']),
                'user' => [
                    'id' => $row['id'],
                    'email' => $row['email'],
                    'full_name' => $row['full_name'],
                    'is_admin' => (bool)$row['is_admin']
                ]
            ];
        }
        
        return false;
    }

    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
