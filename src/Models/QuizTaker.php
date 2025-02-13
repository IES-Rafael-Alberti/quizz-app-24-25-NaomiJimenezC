<?php

class QuizTaker
{
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Evaluar las respuestas del estudiante
    public function evaluateQuiz($quiz_id, $answers) {
        $score = 0;
        $query = "SELECT question_id, correct_option FROM questions WHERE quiz_id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quiz_id' => $quiz_id]);
        $questions = $stmt->fetchAll();

        foreach ($questions as $question) {
            $question_id = $question['question_id'];
            if (isset($answers[$question_id]) && $answers[$question_id] == $question['correct_option']) {
                $score++;
            }
        }

        echo "Tu puntuación es: " . $score;
        return $score;
    }
}