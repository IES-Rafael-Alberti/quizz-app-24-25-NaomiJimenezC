<?php
require_once 'Models/Quizz.php'; // Incluye la clase Quizz

// Instancia de la clase Quizz
$quizz = new Quizz();

// Obtiene todos los cuestionarios de la base de datos
$quizzes = $quizz->getAllQuizzes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cuestionarios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>Lista de Cuestionarios</h1>

<?php if (!empty($quizzes)): ?>
    <!-- Tabla para mostrar los cuestionarios -->
    <table>
        <thead>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($quizzes as $quiz): ?>
            <tr>
                <td>
                    <a href="cuestionario.php?quiz_id=<?php echo urlencode($quiz['quiz_id']); ?>">
                        <?php echo htmlspecialchars($quiz['title']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($quiz['description']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Mensaje si no hay cuestionarios -->
    <p>No hay cuestionarios disponibles.</p>
<?php endif; ?>
</body>
</html>
