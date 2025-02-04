<?php

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
        $query = "INSERT INTO quizzes (title, description) VALUES (:title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description
        ]);
        echo "Cuestionario creado exitosamente.";
    }

    // Obtener todas las preguntas de un cuestionario
    public function getQuestions($quiz_id) {
        $query = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        return $stmt->fetchAll();
    }

    // Agregar una nueva pregunta al cuestionario
    public function addQuestion($quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option) {
        $query = "INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                  VALUES (:quiz_id, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option)";
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
}