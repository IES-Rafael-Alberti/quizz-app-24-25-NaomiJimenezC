<?php
require_once '../Models/User.php';

class UserRoutes
{
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function handleRequest() {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'register':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    $this->user->register($username, $password);
                } else {
                    echo "Método no permitido.";
                }
                break;

            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';
                    $result = $this->user->login($username, $password);

                    if ($result === true) {
                        // Login exitoso
                        header("Location: ../index.php");
                    } else {
                        // Error en el login
                        header("Location: ../login.php?error=" . urlencode($result));
                    }
                    exit();
                } else {
                    http_response_code(405); // Método no permitido
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;


            case 'logout':
                $this->user->logout();
                header("Location: ../index.php");
                break;

            default:
                echo "Acción no válida.";
                break;
        }
    }
}

$routes = new UserRoutes();
$routes->handleRequest();