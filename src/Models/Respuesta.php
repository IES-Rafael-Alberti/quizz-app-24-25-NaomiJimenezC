<?php
require_once 'Database.php';

class Respuesta
{
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Guardar una nueva respuesta
    public function saveResponse($user_id, $quiz_id, $question_id, $selected_option, $is_correct) {
        $query = 'INSERT INTO "Respuestas" (user_id, quiz_id, question_id, selected_option, is_correct)
                  VALUES (:user_id, :quiz_id, :question_id, :selected_option, :is_correct)';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':quiz_id' => $quiz_id,
            ':question_id' => $question_id,
            ':selected_option' => $selected_option,
            ':is_correct' => $is_correct
        ]);
        echo "Respuesta guardada exitosamente.";
    }

    // Obtener todas las respuestas de un usuario para un cuestionario
    public function getResponsesByUserAndQuiz($user_id, $quiz_id) {
        $query = 'SELECT * FROM "Respuestas" WHERE user_id = :user_id AND quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':quiz_id' => $quiz_id
        ]);
        return $stmt->fetchAll();
    }

    // Obtener una respuesta específica por ID
    public function getResponseById($response_id) {
        $query = 'SELECT * FROM "Respuestas" WHERE response_id = :response_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':response_id' => $response_id]);
        return $stmt->fetch();
    }

    // Actualizar una respuesta
    public function updateResponse($response_id, $selected_option, $is_correct) {
        $query = 'UPDATE "Respuestas" SET 
                  selected_option = :selected_option,
                  is_correct = :is_correct
                  WHERE response_id = :response_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':response_id' => $response_id,
            ':selected_option' => $selected_option,
            ':is_correct' => $is_correct
        ]);
        echo "Respuesta actualizada exitosamente.";
    }

    // Eliminar una respuesta por ID
    public function deleteResponse($response_id) {
        $query = 'DELETE FROM "Respuestas" WHERE response_id = :response_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':response_id' => $response_id]);
        echo "Respuesta eliminada exitosamente.";
    }

    // Obtener estadísticas de un cuestionario (respuestas correctas e incorrectas)
    public function getQuizStatistics($quiz_id) {
        $query = 'SELECT 
                    COUNT(*) AS total_responses,
                    SUM(CASE WHEN is_correct THEN 1 ELSE 0 END) AS correct_responses,
                    SUM(CASE WHEN NOT is_correct THEN 1 ELSE 0 END) AS incorrect_responses
                  FROM "Respuestas"
                  WHERE quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        return $stmt->fetch();
    }
}
