<?php
    $quiz_id = $_GET['quiz_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Pregunta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<form action="Routes/QuizzRoutes.php" method="POST">
    <h2>Agregar Pregunta</h2>

    <input type="hidden" name="action" value="add_question">
    <input type="hidden" name="quiz_id" id="quiz_id" value="<?php echo $quiz_id;?>" required>

    <label for="question_text">Pregunta:</label>
    <input type="text" name="question_text" id="question_text" required>

    <label for="option_a">Opción A:</label>
    <input type="text" name="option_a" id="option_a" required>

    <label for="option_b">Opción B:</label>
    <input type="text" name="option_b" id="option_b" required>

    <label for="option_c">Opción C:</label>
    <input type="text" name="option_c" id="option_c" required>

    <label for="option_d">Opción D:</label>
    <input type="text" name="option_d" id="option_d" required>

    <label for="correct_option">Opción Correcta:</label>
    <select name="correct_option" id="correct_option" required>
        <option value="a">Opción A</option>
        <option value="b">Opción B</option>
        <option value="c">Opción C</option>
        <option value="d">Opción D</option>
    </select>

    <button type="submit">Agregar Pregunta</button>
</form>

</body>
</html>
