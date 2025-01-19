<?php
class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($naam, $email, $wachtwoord) {
        $hashed_password = password_hash($wachtwoord, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO users (naam, email, wachtwoord) VALUES (?, ?, ?)");
            return $stmt->execute([$naam, $email, $hashed_password]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($email, $wachtwoord) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_naam'] = $user['naam'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
        return true;
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) return null;
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
} 