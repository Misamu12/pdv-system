<?php
session_start();

class Auth {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            // Pour les tests, on compare en clair. En production, utiliser password_hash et password_verify !
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['store_id'] = $user['store_id'];
                return true;
            }
        }
        return false;
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
    
    public function hasAnyRole($roles) {
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
    }
    
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'name' => $_SESSION['user_name'],
                'role' => $_SESSION['user_role'],
                'store_id' => $_SESSION['store_id']
            ];
        }
        return null;
    }
}
?>
