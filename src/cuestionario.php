<?php
require_once './Models/Quizz.php';
$quizz = new Quizz();

$quiz_id = $_GET['quiz_id'] ?? null;

if (!$quiz_id) {
    die("Error: No se proporcionó un ID de cuestionario.");
}

$quiz = $quizz->getQuizById($quiz_id);

if (!$quiz) {
    die("Error: El cuestionario no existe.");
}

$questions = $quizz->getQuestions($quiz_id);

// Asumimos que tienes el ID del usuario en una sesión
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Error: Usuario no autenticado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario - <?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h1>Preguntas del cuestionario: <?php echo htmlspecialchars($quiz['title']); ?></h1>
    <?php if ($_SESSION['is_admin'] == 1) { ?>
        <a href="formQuestion.php?quiz_id=<?php echo urlencode($quiz['quiz_id']); ?>">
            <button>Añadir preguntas</button>
        </a>
    <?php } ?>
    <?php if ($questions && count($questions) > 0): ?>
        <form action="./Routes/QuizzRoutes.php" method="post" id="quiz-form">
            <input type="hidden" name="action" value="submit_answers">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

            <?php foreach ($questions as $question): ?>
                <div class="question" data-correct-answer="<?php echo $question['correct_option']; ?>">
                    <p><strong><?php echo htmlspecialchars($question['question_text']); ?></strong></p>

                    <?php
                    $options = ['a', 'b', 'c', 'd'];
                    foreach ($options as $option):
                        $option_text = 'option_' . $option;
                        ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="<?php echo $option; ?>">
                            <?php echo htmlspecialchars($question[$option_text]); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" id="submit-button">Enviar respuestas</button>
        </form>

    <?php else: ?>
        <p>No hay preguntas disponibles para este cuestionario.</p>
    <?php endif; ?>

    </body>
</html>
