<?php
class Article {
    private $conn;
    private $table_name = "Articles";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($limit = 10, $offset = 0) {
        $query = "SELECT a.*, u.username as author_name, c.category_name 
                 FROM " . $this->table_name . " a
                 LEFT JOIN Users u ON a.author_id = u.id
                 LEFT JOIN Categories c ON a.category_id = c.id
                 ORDER BY a.created_at DESC
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function getById($id) {
        $query = "SELECT a.*, u.username as author_name, c.category_name 
                 FROM " . $this->table_name . " a
                 LEFT JOIN Users u ON a.author_id = u.id
                 LEFT JOIN Categories c ON a.category_id = c.id
                 WHERE a.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content, $author_id, $category_id) {
        $query = "INSERT INTO " . $this->table_name . " (title, content, author_id, category_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$title, $content, $author_id, $category_id]);
    }
}
?>