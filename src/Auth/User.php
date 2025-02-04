<?php

class User
{
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Registrar usuario con contraseña encriptada
    public function register($username, $password) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password_hashed
        ]);
        echo "Usuario registrado exitosamente.";
    }

    // Iniciar sesión
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            echo "Bienvenido " . $user['username'];
        } else {
            echo "Credenciales incorrectas.";
        }
    }

    // Cerrar sesión
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        echo "Sesión cerrada.";
    }
}