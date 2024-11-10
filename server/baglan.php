<?php
session_start();

class Database {
    private $host = "localhost";
    private $db_name = "axeprime";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
        }

        return $this->conn;
    }

    public function login($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];
                    return true;
                }
            }
            return false;
        } catch(PDOException $e) {
            echo "Giriş hatası: " . $e->getMessage();
            return false;
        }
    }

    public function register($firstname, $lastname, $email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return "Bu e-posta adresi zaten kayıtlı!";
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password, created_at) 
                                        VALUES (:firstname, :lastname, :email, :password, NOW())");
            
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);

            if($stmt->execute()) {
                return "success";
            } else {
                return "Kayıt işlemi başarısız!";
            }
        } catch(PDOException $e) {
            return "Kayıt hatası: " . $e->getMessage();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}