<?php
require_once 'Database.php';

class Quizz
{
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Crear un nuevo cuestionario
    public function createQuiz($title, $description) {
        $query = 'INSERT INTO "Cuestionario" (title, description) VALUES (:title, :description)';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description
        ]);
        echo "Cuestionario creado exitosamente.";
    }

    // Obtener todos los cuestionarios
    public function getAllQuizzes() {
        $query = 'SELECT * FROM "Cuestionario"';
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    // Obtener un cuestionario por ID
    public function getQuizById($quiz_id) {
        $query = 'SELECT * FROM "Cuestionario" WHERE quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        return $stmt->fetch();
    }

    // Actualizar un cuestionario
    public function updateQuiz($quiz_id, $title, $description) {
        $query = 'UPDATE "Cuestionario" SET title = :title, description = :description WHERE quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':quiz_id' => $quiz_id,
            ':title' => $title,
            ':description' => $description
        ]);
        echo "Cuestionario actualizado exitosamente.";
    }

    // Eliminar un cuestionario
    public function deleteQuiz($quiz_id) {
        $query = 'DELETE FROM "Cuestionario" WHERE quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        echo "Cuestionario eliminado exitosamente.";
    }

    // Obtener todas las preguntas de un cuestionario
    public function getQuestions($quiz_id) {
        $query = 'SELECT * FROM "Pregunta" WHERE quiz_id = :quiz_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        return $stmt->fetchAll();
    }

    // Agregar una nueva pregunta al cuestionario
    public function addQuestion($quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option) {
        $query = 'INSERT INTO "Pregunta" (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
                  VALUES (:quiz_id, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option)';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':quiz_id' => $quiz_id,
            ':question_text' => $question_text,
            ':option_a' => $option_a,
            ':option_b' => $option_b,
            ':option_c' => $option_c,
            ':option_d' => $option_d,
            ':correct_option' => $correct_option
        ]);
        echo "Pregunta añadida exitosamente.";
    }

    // Obtener una pregunta por ID
    public function getQuestionById($question_id) {
        $query = 'SELECT * FROM "Pregunta" WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':question_id' => $question_id]);
        return $stmt->fetch();
    }

    // Actualizar una pregunta
    public function updateQuestion($question_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option) {
        $query = 'UPDATE "Pregunta" SET 
                  question_text = :question_text, 
                  option_a = :option_a, 
                  option_b = :option_b, 
                  option_c = :option_c, 
                  option_d = :option_d, 
                  correct_option = :correct_option
                  WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':question_id' => $question_id,
            ':question_text' => $question_text,
            ':option_a' => $option_a,
            ':option_b' => $option_b,
            ':option_c' => $option_c,
            ':option_d' => $option_d,
            ':correct_option' => $correct_option
        ]);
        echo "Pregunta actualizada exitosamente.";
    }

    // Eliminar una pregunta
    public function deleteQuestion($question_id) {
        $query = 'DELETE FROM "Pregunta" WHERE question_id = :question_id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':question_id' => $question_id]);
        echo "Pregunta eliminada exitosamente.";
    }
}
