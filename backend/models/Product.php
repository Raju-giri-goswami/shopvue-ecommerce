<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    // Product properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $image_url;
    public $stock;
    public $category;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTableName() {
        return $this->table_name;
    }

    // Get all products with pagination
    public function getAll($page = 1, $per_page = 12, $category = null, $search = null) {
        $offset = ($page - 1) * $per_page;
        
        $query = "SELECT * FROM " . $this->table_name;
        $params = array();
        
        if ($category || $search) {
            $query .= " WHERE ";
            if ($category) {
                $query .= "category = :category";
                $params[':category'] = $category;
            }
            if ($search) {
                if ($category) $query .= " AND ";
                $query .= "(name LIKE :search OR description LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
        }
        
        // Get total count for pagination
        $count_query = str_replace("SELECT *", "SELECT COUNT(*)", $query);
        $stmt = $this->conn->prepare($count_query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $total_count = $stmt->fetchColumn();
        
        // Get paginated results
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'products' => $products,
            'total' => $total_count,
            'page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil($total_count / $per_page)
        ];
    }

    // Get single product
    public function getOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create product
    public function create() {
        $this->id = Auth::generateUUID();
        
        $query = "INSERT INTO " . $this->table_name . "
                (id, name, description, price, image_url, stock, category)
                VALUES
                (:id, :name, :description, :price, :image_url, :stock, :category)";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category", $this->category);

        return $stmt->execute();
    }

    // Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    name = :name,
                    description = :description,
                    price = :price,
                    image_url = :image_url,
                    stock = :stock,
                    category = :category,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Delete product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
