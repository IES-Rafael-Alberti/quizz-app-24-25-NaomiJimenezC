<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<form action="./Routes/UserRoutes.php" method="POST">
    <h2>Iniciar Sesión</h2>

    <?php
        if (isset($_GET['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
    }
    ?>

    <input type="hidden" name="action" value="login">

    <label for="username">Nombre de usuario:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Iniciar Sesión</button>
</form>
</body>
</html>