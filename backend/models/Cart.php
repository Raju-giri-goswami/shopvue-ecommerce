<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Auth.php';

class Cart {
    private $conn;
    private $table_name = "cart_items";

    public $id;
    public $user_id;
    public $product_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get cart items for a user
    public function getUserCart($user_id) {
        $query = "SELECT ci.*, 
                        p.name, p.price, p.image_url, p.stock, p.category
                 FROM " . $this->table_name . " ci
                 LEFT JOIN products p ON ci.product_id = p.id
                 WHERE ci.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = 0;

        foreach ($items as &$item) {
            $item['total_price'] = $item['price'] * $item['quantity'];
            $total += $item['total_price'];
        }

        return [
            'items' => $items,
            'total' => $total,
            'item_count' => count($items)
        ];
    }

    // Add item to cart
    public function addToCart() {
        // Check if item already exists in cart
        $check_query = "SELECT id, quantity FROM " . $this->table_name . " 
                       WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->conn->prepare($check_query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Update quantity if item exists
            $new_quantity = $row['quantity'] + $this->quantity;
            $query = "UPDATE " . $this->table_name . "
                     SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":quantity", $new_quantity);
            $stmt->bindParam(":id", $row['id']);
            
            return $stmt->execute();
        } else {
            // Insert new item if it doesn't exist
            $this->id = Auth::generateUUID();
            
            $query = "INSERT INTO " . $this->table_name . "
                    (id, user_id, product_id, quantity)
                    VALUES (:id, :user_id, :product_id, :quantity)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":product_id", $this->product_id);
            $stmt->bindParam(":quantity", $this->quantity);

            return $stmt->execute();
        }
    }

    // Update cart item quantity
    public function updateQuantity($item_id, $quantity) {
        $query = "UPDATE " . $this->table_name . "
                 SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $item_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    // Remove item from cart
    public function removeFromCart($item_id) {
        $query = "DELETE FROM " . $this->table_name . "
                 WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $item_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }

    // Clear user's cart
    public function clearCart() {
        $query = "DELETE FROM " . $this->table_name . "
                 WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }
}
