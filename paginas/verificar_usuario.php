<?php
session_start();
require '../funciones/conexion.php'; // Incluir archivo de conexión

$error_message = ''; // Inicializar mensaje de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    // Verificar si el nombre de usuario existe en la base de datos
    $stmt = $conn->prepare("SELECT pregunta1, pregunta2 FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nombre de usuario encontrado, obtener preguntas de seguridad
        $user = $result->fetch_assoc();
        $_SESSION['reset_username'] = $username; // Guardar el nombre de usuario en sesión

        // Redirigir a la página para responder las preguntas de seguridad
        header("Location: responder_preguntas.php?username=$username&pregunta1={$user['pregunta1']}&pregunta2={$user['pregunta2']}");
        exit();
    } else {
        echo "<script>alert('Usuario no existente.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Usuario</title>
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
            <h2>Recuperar Contraseña</h2>
            <div class="input-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <button type="submit">Verificar Usuario</button>
        </form>
        <div class="register-link">
            <p>Cancelar <a href="Index.php"></a></p>
        </div>
    </div>
</body>
</html>
