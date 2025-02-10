<?php
require_once '../Models/Quizz.php';

class QuizzRoutes
{
    private $quizz;

    public function __construct() {
        $this->quizz = new Quizz();
    }

    public function handleRequest() {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'create_quiz':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $title = $_POST['title'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $this->quizz->createQuiz($title, $description);
                    echo json_encode(["message" => "Cuestionario creado exitosamente"]);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'get_quizzes':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $quizzes = $this->quizz->getAllQuizzes();
                    echo json_encode($quizzes);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'get_quiz_by_id':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $quiz_id = $_GET['quiz_id'] ?? '';
                    $quiz = $this->quizz->getQuizById($quiz_id);
                    echo json_encode($quiz);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'update_quiz':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $quiz_id = $_POST['quiz_id'] ?? '';
                    $title = $_POST['title'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $this->quizz->updateQuiz($quiz_id, $title, $description);
                    echo json_encode(["message" => "Cuestionario actualizado"]);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'delete_quiz':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $quiz_id = $_POST['quiz_id'] ?? '';
                    $this->quizz->deleteQuiz($quiz_id);
                    echo json_encode(["message" => "Cuestionario eliminado"]);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'add_question':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $quiz_id = $_POST['quiz_id'] ?? '';
                    $question_text = $_POST['question_text'] ?? '';
                    $option_a = $_POST['option_a'] ?? '';
                    $option_b = $_POST['option_b'] ?? '';
                    $option_c = $_POST['option_c'] ?? '';
                    $option_d = $_POST['option_d'] ?? '';
                    $correct_option = $_POST['correct_option'] ?? '';
                    $this->quizz->addQuestion($quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option);
                    echo json_encode(["message" => "Pregunta añadida exitosamente"]);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            case 'get_questions':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $quiz_id = $_GET['quiz_id'] ?? '';
                    $questions = $this->quizz->getQuestions($quiz_id);
                    echo json_encode($questions);
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                }
                break;

            default:
                echo json_encode(["error" => "Acción no válida"]);
                break;
        }
    }
}

$routes = new QuizzRoutes();
$routes->handleRequest();
