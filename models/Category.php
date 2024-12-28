<?php
class Category {
    private $conn;
    private $table_name = "Categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY category_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($category_name) {
        $query = "INSERT INTO " . $this->table_name . " (category_name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$category_name]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
