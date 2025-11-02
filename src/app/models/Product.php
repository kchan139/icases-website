<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getFeatured($limit = 8) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.is_active = 1
                  ORDER BY p.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getAll($sort = 'newest', $category = null, $model = null, $limit = 12, $offset = 0) {
        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.is_active = 1";
        
        if ($category) {
            $query .= " AND c.slug = :category";
        }
        if ($model) {
            $query .= " AND p.iphone_model = :model";
        }
        
        switch ($sort) {
            case 'price-asc':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'price-desc':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'name':
                $query .= " ORDER BY p.name ASC";
                break;
            default:
                $query .= " ORDER BY p.created_at DESC";
        }
        
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        if ($category) $stmt->bindParam(':category', $category);
        if ($model) $stmt->bindParam(':model', $model);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getTotalCount($category = null, $model = null) {
        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.is_active = 1";
        
        if ($category) {
            $query .= " AND c.slug = :category";
        }
        if ($model) {
            $query .= " AND p.iphone_model = :model";
        }
        
        $stmt = $this->conn->prepare($query);
        if ($category) $stmt->bindParam(':category', $category);
        if ($model) $stmt->bindParam(':model', $model);
        $stmt->execute();
        
        return $stmt->fetch()['total'];
    }

    public function getCategories() {
        $query = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getIphoneModels() {
        $query = "SELECT DISTINCT iphone_model FROM " . $this->table . " 
                  WHERE is_active = 1 ORDER BY iphone_model ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($query, $limit = 10) {
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM " . $this->table . " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1 
                AND (p.name LIKE :search 
                     OR p.iphone_model LIKE :search
                     OR p.description LIKE :search
                     OR c.name LIKE :search)
                ORDER BY p.name ASC
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getBySlug($slug) {
        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.slug = :slug AND p.is_active = 1
                  LIMIT 1";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
    
        return $stmt->fetch();
    }

    public function getStoreAvailability($productId) {
        $query = "SELECT s.*, psa.quantity
                  FROM stores s
                  INNER JOIN product_store_availability psa ON s.id = psa.store_id
                  WHERE psa.product_id = :product_id AND s.is_active = 1
                  ORDER BY s.city, s.name";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll();
    }
}
