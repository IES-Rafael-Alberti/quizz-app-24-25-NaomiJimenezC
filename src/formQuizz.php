<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuestionario</title>

</head>
<body>
<h2>Crear Cuestionario</h2>

<?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1): ?>
    <p style="color: red;">Acceso denegado. Solo administradores pueden crear cuestionarios.</p>
<?php else: ?>
    <form action="Routes/QuizzRoutes.php" id="quizForm" method="POST" onsubmit="goBack();">
        <input type="hidden" name="action" value="create_quiz">
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Descripción:</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">Crear</button>
    </form>
<?php endif; ?>
</body>
</html>

