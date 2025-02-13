<?php
require_once '../Models/Quizz.php';
require_once '../Models/Respuesta.php';

class QuizzRoutes
{
    private $quizz;
    private $respuesta;

    public function __construct() {
        $this->quizz = new Quizz();
        $this->respuesta = new Respuesta();
    }

    public function handleRequest() {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'create_quiz':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $title = $_POST['title'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $this->quizz->createQuiz($title, $description);
                    header('Location: ../cuestionarios.php');

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
                    header("Location: ../cuestionario?quiz_id=$quiz_id.php");
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

            case 'submit_answers':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $user_id = $_POST['user_id'] ?? '';
                    $quiz_id = $_POST['quiz_id'] ?? '';
                    $answers = $_POST['answers'] ?? [];

                    // Validar que los datos necesarios estén presentes
                    if (empty($user_id) || empty($quiz_id) || empty($answers)) {
                        echo json_encode(["error" => "Datos incompletos: user_id, quiz_id o answers faltan"]);
                        exit;
                    }

                    // Inicializar un array para almacenar los resultados
                    $results = [];
                    $correct_count = 0;

                    foreach ($answers as $question_id => $selected_option) {
                        // Obtener los datos de la pregunta
                        $question = $this->quizz->getQuestionById($question_id);

                        if (!$question) {
                            echo json_encode(["error" => "Pregunta no encontrada: $question_id"]);
                            exit;
                        }

                        $is_correct = strtolower($question['correct_option']) == strtolower($selected_option);

                        // Asegurar que $is_correct se guarde como 1 o 0 en la BD
                        $is_correct = $is_correct ? 1 : 0;

                        // Guardar la respuesta en la base de datos
                        try {
                            $this->respuesta->saveResponse($user_id, $quiz_id, $question_id, $selected_option, $is_correct);
                            if ($is_correct) {
                                $correct_count++;
                            }
                            $results[] = [
                                'question' => $question['question_text'],
                                'user_answer' => $selected_option,
                                'is_correct' => $is_correct,
                                'correct_answer' => $question['correct_option']
                            ];
                        } catch (Exception $e) {
                            echo json_encode(["error" => "Error al guardar respuesta: " . $e->getMessage()]);
                            exit;
                        }
                    }

                    // Calcular estadísticas del cuestionario
                    $quiz_statistics = $this->respuesta->getQuizStatistics($quiz_id, $user_id);

                    if (isset($quiz_statistics['average_score'])) {
                        $quiz_statistics['average_score'] = round($quiz_statistics['average_score'], 2);
                    }

                    // Devolver los resultados y estadísticas como respuesta JSON
                    echo json_encode([
                        "message" => "Respuestas procesadas exitosamente",
                        "correct_count" => $correct_count,
                        "total_questions" => count($answers),
                        "results" => $results,
                        "quiz_statistics" => $quiz_statistics
                    ]);
                    exit;
                } else {
                    echo json_encode(["error" => "Método no permitido"]);
                    exit;
                }
            default:
                echo json_encode(["error" => "Acción no válida"]);
                break;
        }
    }
}

$routes = new QuizzRoutes();
$routes->handleRequest();
