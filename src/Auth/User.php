<?php
require_once "Database.php";
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
        try {
            // Verificar si el usuario ya existe
            $checkQuery = 'SELECT COUNT(*) FROM "Usuario" WHERE username = :username';
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':username' => $username]);

            if ($checkStmt->fetchColumn() > 0) {
                throw new Exception("El nombre de usuario ya existe.");
            }

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = 'INSERT INTO "Usuario" (username, password) VALUES (:username, :password)';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':username' => $username,
                ':password' => $password_hashed
            ]);

            // Si todo va bien, redirigir
            header("Location: ../index.html");
            exit();
        } catch (PDOException $e) {
            // Error de base de datos
            error_log("Error de base de datos: " . $e->getMessage());
            return "Error al registrar el usuario. Por favor, inténtelo de nuevo más tarde.";
        } catch (Exception $e) {
            // Otros errores
            return $e->getMessage();
        }
    }

    // Iniciar sesión
    public function login($username, $password) {
        try {
            $query = 'SELECT * FROM "Usuario" WHERE username = :username';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                return true; // Login exitoso
            } else {
                return "Credenciales incorrectas.";
            }
        } catch (PDOException $e) {
            error_log("Error de base de datos en login: " . $e->getMessage());
            return "Error en el servidor. Por favor, inténtelo más tarde.";
        } catch (Exception $e) {
            error_log("Error general en login: " . $e->getMessage());
            return "Ocurrió un error inesperado. Por favor, inténtelo de nuevo.";
        }
    }


    // Cerrar sesión
    public function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            header("../index.html");
        }

    }
}