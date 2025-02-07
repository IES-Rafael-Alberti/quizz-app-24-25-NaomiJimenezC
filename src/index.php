<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <title>Inicio</title>
</head>
<body>
<h1>Bienvenidos a QuizzApp</h1>
    <ul>
        <?php if (isset($_SESSION['user_id'])): ?>
            <section>
                <h2>Bienvenido/a <?php print_r($_SESSION['username'])?></h2>
            </section>
            <form action="./Routes/UserRoutes.php" method="POST">
                <input type="hidden" name="action" value="logout">
                <button type="submit">Cerrar Sesión</button>
            </form>
        <?php else: ?>
            <li>
                <a href="login.php">Login</a>
                <a href="register.html">Registro</a>
            </li>
        <?php endif; ?>
    </ul>
</body>
</html>
