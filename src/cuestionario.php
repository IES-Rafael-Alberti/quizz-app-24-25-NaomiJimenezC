<?php
require_once './Models/Quizz.php';
session_start();

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
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Error: Usuario no autenticado.");
}

// Procesar respuestas cuando el formulario es enviado
$results = [];
$correct_count = 0;
$total_questions = count($questions);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    $answers = $_POST['answers'];
    foreach ($questions as $question) {
        $question_id = $question['question_id'];
        $correct_answer = $question['correct_option'];
        $user_answer = $answers[$question_id] ?? null;
        $is_correct = $user_answer === $correct_answer;
        if ($is_correct) {
            $correct_count++;
        }
        $results[] = [
            'question' => $question['question_text'],
            'correct_answer' => $correct_answer,
            'user_answer' => $user_answer,
            'is_correct' => $is_correct
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario - <?php echo htmlspecialchars($quiz['title']); ?></title>
</head>
<body>

<h1>Preguntas del cuestionario: <?php echo htmlspecialchars($quiz['title']); ?></h1>

<?php if ($_SESSION['is_admin'] == 1) { ?>
    <a href="formQuestion.php?quiz_id=<?php echo urlencode($quiz['quiz_id']); ?>">
        <button>Añadir preguntas</button>
    </a>
<?php } ?>

<?php if ($questions && count($questions) > 0): ?>
    <form action="" method="post" id="quiz-form">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php foreach ($questions as $question): ?>
            <div class="question">
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

    <?php if (!empty($results)): ?>
        <h2>Resumen de respuestas</h2>
        <p><strong>Has acertado <?php echo $correct_count; ?> de <?php echo $total_questions; ?></strong></p>
        <ul>
            <?php foreach ($results as $result): ?>
                <li>
                    <strong><?php echo htmlspecialchars($result['question']); ?></strong><br>
                    Tu respuesta:
                    <span style="color: <?php echo $result['is_correct'] ? 'green' : 'red'; ?>">
                            <?php echo htmlspecialchars($result['user_answer'] ?? 'No respondida'); ?>
                        </span><br>
                    Respuesta correcta: <strong><?php echo htmlspecialchars($result['correct_answer']); ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php else: ?>
    <p>No hay preguntas disponibles para este cuestionario.</p>
<?php endif; ?>

</body>
</html>
