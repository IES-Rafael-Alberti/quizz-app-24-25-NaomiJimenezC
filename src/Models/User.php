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
            // Verificar si los campos están vacíos
            if (empty($username) || empty($password)) {
                throw new Exception("El nombre de usuario o la contraseña no pueden estar vacíos.");
            }

            // Verificar si el usuario ya existe
            $checkQuery = 'SELECT COUNT(*) FROM "Usuario" WHERE username = :username';
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':username' => $username]);

            if ($checkStmt->fetchColumn() > 0) {
                throw new Exception("El nombre de usuario ya existe.");
            }

            // Generar hash de contraseña
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            error_log("Password hash generado: $password_hashed");

            // Insertar nuevo usuario
            $query = 'INSERT INTO "Usuario" (username, password, is_administrator) VALUES (:username, :password, :is_administrator)';
            $stmt = $this->conn->prepare($query);

            // Depuración: Registrar intento
            error_log("Intentando registrar: username=$username");

            // Ejecutar la consulta
            $stmt->execute([
                ':username' => $username,
                ':password' => $password_hashed,
                ':is_administrator' => 'f' // 'f' es equivalente a false en PostgreSQL
            ]);

            // Redirigir al index.php después del registro exitoso
            header("Location: ../index.php");
            exit();
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            error_log("Error de base de datos: " . $e->getMessage());
            return "Error al registrar el usuario. Por favor, inténtelo de nuevo más tarde.";
        } catch (Exception $e) {
            // Manejo de otros errores
            error_log("Error general: " . $e->getMessage());
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
                $_SESSION['is_admin'] = $user['is_administrator'];
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
        // Start session only if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Redirect and stop execution
        header("Location: ../index.php");
        exit;
    }
}