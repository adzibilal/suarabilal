<?php
class User {
    private $conn;
    private $table_name = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT u.id, u.username, u.password, u.role_id, r.role_name 
                 FROM " . $this->table_name . " u
                 JOIN Roles r ON u.role_id = r.id 
                 WHERE u.username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }

    public function register($username, $password, $email, $role_id = 3) { // Default to user role
        $query = "INSERT INTO " . $this->table_name . " (username, password, email, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        return $stmt->execute([$username, $hashed_password, $email, $role_id]);
    }

    public function hasRole($required_roles) {
        if (!isset($_SESSION['role_id'])) return false;
        return in_array($_SESSION['role_id'], (array)$required_roles);
    }
}
?>
