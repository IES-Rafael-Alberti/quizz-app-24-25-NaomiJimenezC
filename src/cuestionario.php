<?php
require_once './Models/Quizz.php';
require_once './Models/Respuesta.php';
session_start();

$quizz = new Quizz();
$respuesta = new Respuesta(); // Crear una instancia de Respuesta

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

// Obtener estadísticas del cuestionario y usuario llamando correctamente al método desde la clase Respuesta
$quiz_statistics = $respuesta->getQuizStatistics($quiz_id, $user_id);

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
    <form action="./Routes/QuizzRoutes.php" method="post" id="quiz-form">
        <input type="hidden" name="action" value="submit_answers">
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

    <div id="results-container"></div> <!-- Contenedor para los resultados -->

<?php else: ?>
    <p>No hay preguntas disponibles para este cuestionario.</p>
<?php endif; ?>

<script>
    // Esperar a que el documento esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        const quizForm = document.getElementById('quiz-form'); // Formulario del cuestionario
        const resultsContainer = document.getElementById('results-container'); // Contenedor de resultados

        // Cuando se envíe el formulario
        quizForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

            const formData = new FormData(quizForm); // Recoger todos los datos del formulario

            // Usar la API fetch para enviar la solicitud con los datos
            fetch('./Routes/QuizzRoutes.php', {
                method: 'POST',
                body: formData // Enviar los datos del formulario
            })
                .then(response => response.json()) // Esperar la respuesta JSON
                .then(data => {
                    if (data.error) {
                        // Si hay un error, mostrarlo
                        resultsContainer.innerHTML = `<p style="color: red;">Error: ${data.error}</p>`;
                    } else {
                        // Si no hay error, mostrar el resumen y las estadísticas
                        let resultHtml = `<h2>Resumen de respuestas</h2>`;
                        resultHtml += `<p><strong>Has acertado ${data.correct_count} de ${data.total_questions}</strong></p>`;

                        // Mostrar estadísticas adicionales del cuestionario
                        if (data.quiz_statistics) {
                            resultHtml += `<p><strong>Puntuación media del cuestionario: ${data.quiz_statistics.average_score}%</strong></p>`;
                            if (data.quiz_statistics.total_attempts !== undefined) {
                                resultHtml += `<p><strong>Intentos totales: ${data.quiz_statistics.total_attempts}</strong></p>`;
                            } else {
                                resultHtml += `<p><strong>No hay intentos registrados aún.</strong></p>`;
                            }
                        }

                        // Mostrar las respuestas del cuestionario con color (verde para correctas, rojo para incorrectas)
                        resultHtml += `<ul>`;
                        data.results.forEach(result => {
                            resultHtml += `<li>
                        <strong>${result.question}</strong><br>
                        Tu respuesta: <span style="color: ${result.is_correct ? 'green' : 'red'};">${result.user_answer ?? 'No respondida'}</span><br>
                        Respuesta correcta: <strong>${result.correct_answer}</strong>
                    </li>`;
                        });
                        resultHtml += `</ul>`;

                        // Insertar los resultados en el contenedor
                        resultsContainer.innerHTML = resultHtml;
                    }
                })
                .catch(error => {
                    // En caso de que ocurra un error durante la solicitud AJAX
                    resultsContainer.innerHTML = `<p style="color: red;">Error al procesar la solicitud: ${error.message}</p>`;
                });
        });
    });
</script>

</body>
</html>
