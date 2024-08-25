<?php
session_start();
require '../funciones/conexion.php'; // Incluir archivo de conexión a la base de datos

// Verificar si existe un usuario en sesión para recuperar las preguntas
if (!isset($_SESSION['reset_username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['reset_username'];
$pregunta1 = isset($_GET['pregunta1']) ? $_GET['pregunta1'] : '';
$pregunta2 = isset($_GET['pregunta2']) ? $_GET['pregunta2'] : '';

$error_message = ''; // Variable para almacenar mensajes de error

// Verificar si se ha enviado el formulario con las respuestas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta1 = isset($_POST['respuesta1']) ? $_POST['respuesta1'] : '';
    $respuesta2 = isset($_POST['respuesta2']) ? $_POST['respuesta2'] : '';

    // Validar las respuestas
    $stmt = $conn->prepare("SELECT respuesta1, respuesta2 FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar las respuestas ingresadas con las almacenadas en la base de datos
        if (hash('sha256', $respuesta1) === $user['respuesta1'] && hash('sha256', $respuesta2) === $user['respuesta2']) {
            // Respuestas correctas, redirigir a cambiar contraseña
            header("Location: nueva_contrasena.php?username=$username");
            exit();
        } else {
            // Respuestas incorrectas, mostrar mensaje de error
            echo "<script>alert('Las respuestas a las preguntas de seguridad son incorrectas.');</script>";
        }
    } else {
        // Usuario no encontrado, redirigir al inicio de sesión
        $error_message = "Usuario no encontrado en la base de datos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Preguntas de Seguridad</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<style>
        .register-link {
            margin-top: 10px;
            text-align: center;
        }

        .register-link a {
            color: black;
            text-decoration: none;
        }
    </style>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Respuesta a las Preguntas de Seguridad</h2>
            <div class="input-group">
                <label for="respuesta1"><?php echo htmlspecialchars($pregunta1); ?></label>
                <input type="text" id="respuesta1" name="respuesta1" required>
            </div>
            <div class="input-group">
                <label for="respuesta2"><?php echo htmlspecialchars($pregunta2); ?></label>
                <input type="text" id="respuesta2" name="respuesta2" required>
            </div>
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <button type="submit">Enviar Respuestas</button>
        </form>
        <div class="register-link">
            <p>Cancelar <a href="Index.php"></a></p>
        </div>
    </div>
</body>
</html>
