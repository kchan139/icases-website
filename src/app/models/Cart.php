<?php
class Cart
{
    private $conn;
    private $table = "cart_items";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function getIdentifier()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        return ['user_id' => $userId, 'session_id' => $sessionId];
    }

    public function addItem($productId, $quantity = 1)
    {
        $id = $this->getIdentifier();
        
        // Check if item already exists
        $query = "SELECT id, quantity FROM {$this->table} 
                  WHERE product_id = :product_id 
                  AND " . ($id['user_id'] ? "user_id = :user_id" : "session_id = :session_id");
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        if ($id['user_id']) {
            $stmt->bindParam(':user_id', $id['user_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $id['session_id']);
        }
        $stmt->execute();
        
        if ($existing = $stmt->fetch()) {
            // Update quantity
            $newQty = $existing['quantity'] + $quantity;
            $query = "UPDATE {$this->table} SET quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quantity', $newQty, PDO::PARAM_INT);
            $stmt->bindParam(':id', $existing['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            // Insert new item
            $query = "INSERT INTO {$this->table} (user_id, session_id, product_id, quantity) 
                      VALUES (:user_id, :session_id, :product_id, :quantity)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $id['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':session_id', $id['session_id']);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    public function getItems()
    {
        $id = $this->getIdentifier();
        
        $query = "SELECT c.*, p.name, p.slug, p.price, p.image_url, p.iphone_model, p.stock_quantity
                  FROM {$this->table} c
                  INNER JOIN products p ON c.product_id = p.id
                  WHERE " . ($id['user_id'] ? "c.user_id = :user_id" : "c.session_id = :session_id") . "
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($id['user_id']) {
            $stmt->bindParam(':user_id', $id['user_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $id['session_id']);
        }
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartItemId);
        }
        
        $query = "UPDATE {$this->table} SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $cartItemId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeItem($cartItemId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $cartItemId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCount()
    {
        $id = $this->getIdentifier();
        
        $query = "SELECT SUM(quantity) as total FROM {$this->table} 
                  WHERE " . ($id['user_id'] ? "user_id = :user_id" : "session_id = :session_id");
        
        $stmt = $this->conn->prepare($query);
        if ($id['user_id']) {
            $stmt->bindParam(':user_id', $id['user_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $id['session_id']);
        }
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function clearCart()
    {
        $id = $this->getIdentifier();
        
        $query = "DELETE FROM {$this->table} 
                  WHERE " . ($id['user_id'] ? "user_id = :user_id" : "session_id = :session_id");
        
        $stmt = $this->conn->prepare($query);
        if ($id['user_id']) {
            $stmt->bindParam(':user_id', $id['user_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':session_id', $id['session_id']);
        }
        return $stmt->execute();
    }
}
