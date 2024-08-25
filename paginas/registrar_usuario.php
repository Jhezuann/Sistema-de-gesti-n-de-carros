<?php
session_start();
require '../funciones/conexion.php'; // Archivo que contiene la conexión a la base de datos

// Variables para almacenar los valores del formulario y los errores
$username = $password = $confirm_password = $pregunta1 = $respuesta1 = $pregunta2 = $respuesta2 = '';
$username_err = $password_err = $confirm_password_err = $pregunta1_err = $respuesta1_err = $pregunta2_err = $respuesta2_err = '';

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y guardar los datos del formulario
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $pregunta1 = $_POST['pregunta1'];
    $respuesta1 = $_POST['respuesta1'];
    $pregunta2 = $_POST['pregunta2'];
    $respuesta2 = $_POST['respuesta2'];

    // Validación de nombre de usuario
    if (empty($username)) {
        $username_err = "Por favor, ingrese un nombre de usuario.";
    } else {
        // Verificar si el nombre de usuario ya existe
        $query = "SELECT id FROM usuarios WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $username_err = "Este nombre de usuario ya está en uso.";
        }
        $stmt->close();
    }

    // Validación de contraseña
    if (empty($password)) {
        $password_err = "Por favor, ingrese una contraseña.";
    } elseif (strlen($password) < 8) {
        $password_err = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Validación de confirmación de contraseña
    if (empty($confirm_password)) {
        $confirm_password_err = "Por favor, confirme la contraseña.";
    } elseif ($password != $confirm_password) {
        $confirm_password_err = "Las contraseñas no coinciden.";
    }

    // Validación de preguntas y respuestas de seguridad
    if (empty($pregunta1) || empty($respuesta1) || empty($pregunta2) || empty($respuesta2)) {
        $pregunta1_err = $pregunta2_err = "Por favor, complete todas las preguntas y respuestas de seguridad.";
    }

    // Si no hay errores, proceder a insertar en la base de datos
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($pregunta1_err) && empty($pregunta2_err)) {
        // Hash SHA-256 de la contraseña ingresada
        $hashed_password = hash('sha256', $password);
        // Hash SHA-256 de las respuestas de seguridad
        $hashed_respuesta1 = hash('sha256', $respuesta1);
        $hashed_respuesta2 = hash('sha256', $respuesta2);

        // Insertar usuario en la base de datos
        $query_insert = "INSERT INTO usuarios (username, password, pregunta1, respuesta1, pregunta2, respuesta2) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("ssssss", $username, $hashed_password, $pregunta1, $hashed_respuesta1, $pregunta2, $hashed_respuesta2);

        if ($stmt_insert->execute()) {
            // Mostrar mensaje de éxito y redirigir
            echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error al registrar el usuario: " . $stmt_insert->error . "');</script>";
        }

        $stmt_insert->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../style/style.css">
    <style>
        .input-group input,
        .input-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .register-link {
            margin-top: 10px;
            text-align: center;
        }

        .register-link a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST" class="register-form">
            <h2>Registrar Usuario</h2>
            <div class="input-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="input-group">
                <label for="pregunta1">Pregunta de seguridad 1</label>
                <select id="pregunta1" name="pregunta1" required>
                    <option value="">Seleccione una pregunta</option>
                    <option value="1" <?php if ($pregunta1 == '1') echo 'selected'; ?>>¿Cuál es el nombre de tu mascota?</option>
                    <option value="2" <?php if ($pregunta1 == '2') echo 'selected'; ?>>¿Cuál es tu comida favorita?</option>
                    <option value="3" <?php if ($pregunta1 == '3') echo 'selected'; ?>>¿En qué ciudad naciste?</option>
                    <option value="4" <?php if ($pregunta1 == '4') echo 'selected'; ?>>¿Cuál es tu película favorita?</option>
                </select>
                <span class="help-block"><?php echo $pregunta1_err; ?></span>
            </div>
            <div class="input-group">
                <label for="respuesta1">Respuesta de seguridad 1</label>
                <input type="text" id="respuesta1" name="respuesta1" value="<?php echo htmlspecialchars($respuesta1); ?>" required>
                <span class="help-block"><?php echo $respuesta1_err; ?></span>
            </div>
            <div class="input-group">
                <label for="pregunta2">Pregunta de seguridad 2</label>
                <select id="pregunta2" name="pregunta2" required>
                    <option value="">Seleccione una pregunta</option>
                    <option value="1" <?php if ($pregunta2 == '1') echo 'selected'; ?>>¿Cuál es el nombre de tu mejor amigo/a?</option>
                    <option value="2" <?php if ($pregunta2 == '2') echo 'selected'; ?>>¿Dónde fue tu primera vacación?</option>
                    <option value="3" <?php if ($pregunta2 == '3') echo 'selected'; ?>>¿Cuál es tu libro favorito?</option>
                    <option value="4" <?php if ($pregunta2 == '4') echo 'selected'; ?>>¿Cuál es tu deporte favorito?</option>
                </select>
                <span class="help-block"><?php echo $pregunta2_err; ?></span>
            </div>
            <div class="input-group">
                <label for="respuesta2">Respuesta de seguridad 2</label>
                <input type="text" id="respuesta2" name="respuesta2" value="<?php echo htmlspecialchars($respuesta2); ?>" required>
                <span class="help-block"><?php echo $respuesta2_err; ?></span>
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <div class="register-link">
            <p>¿Ya tienes cuenta? <a href="index.php">Ingresa aquí</a></p>
        </div>
    </div>
</body>
</html>
