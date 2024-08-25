<?php
session_start();
require '../funciones/conexion.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash SHA-256 de la contraseña ingresada
    $hashed_password = hash('sha256', $password);

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Usuario encontrado, verificar contraseña
        $user = $result->fetch_assoc();
        if ($hashed_password === $user['password']) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo "<script>alert('Bienvenido " . $user['username'] . "'); window.location.href='inicio.php';</script>";
            exit();
        } else {
            echo "<script>alert('Su contraseña es incorrecta.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no existente.'); window.location.href='index.php';</script>";
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../style/style.css">
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
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h2>Iniciar Sesión</h2>
            <div class="input-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar</button>
        </form>
        <div class="register-link">
            <p>¿No tienes cuenta? <a href="registrar_usuario.php">Regístrate aquí</a></p>
        </div>
        <div class="register-link">
            <p>¿Olvidaste tu contraseña? <a href="verificar_usuario.php">Recupérala aquí</a></p>
        </div>
    </div>
</body>
</html>
