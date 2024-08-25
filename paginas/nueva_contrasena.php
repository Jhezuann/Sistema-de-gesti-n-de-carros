<?php
session_start();
require '../funciones/conexion.php'; // Incluir archivo de conexión a la base de datos

// Verificar si existe un usuario en sesión para recuperar el nombre de usuario
if (!isset($_SESSION['reset_username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['reset_username'];

// Verificar si se ha enviado el formulario con la nueva contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validar la contraseña y la confirmación
    if (empty($password) || empty($confirm_password)) {
        echo "<script>alert('Por favor, ingresa una nueva contraseña y confírmala.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');</script>";
    } else {
        // Cifrar la contraseña con SHA-256
        $hashed_password = hash('sha256', $password);

        // Actualizar la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
        
        // Verificar la preparación de la consulta
        if (!$stmt) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }
        
        // Vincular parámetros y ejecutar la consulta
        $stmt->bind_param("ss", $hashed_password, $username);
        $stmt->execute();

        // Verificar la ejecución de la consulta
        if ($stmt->affected_rows > 0) {
            // Redirigir a la página de inicio de sesión u otra página deseada
            echo "<script>alert('Contraseña actualizada con exito.'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error en la contraseña... pruebe con otra.');</script>";
        }
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
    <title>Cambiar Contraseña</title>
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
            <h2>Cambiar Contraseña</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="input-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Cambiar Contraseña</button>
        </form>
        <div class="register-link">
            <p>Cancelar <a href="Index.php"></a></p>
        </div>
    </div>
</body>
</html>
