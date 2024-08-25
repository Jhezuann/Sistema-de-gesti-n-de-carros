<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Solo permitir acceso a administradores
if ($role != 'admin') {
    header("Location: inicio.php");
    exit();
}

// Conectar a la base de datos
require '../funciones/conexion.php';

// Variables para almacenar los valores del formulario y los errores
$nombre = '';
$nombre_err = '';

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y guardar los datos del formulario
    $nombre = trim($_POST['nombre']);

    // Validación de nombre
    if (empty($nombre)) {
        $nombre_err = "Por favor, ingrese un nombre para la pieza.";
    }

    // Si no hay errores, proceder a insertar en la base de datos
    if (empty($nombre_err)) {
        $query = "INSERT INTO partes_carro (nombre) VALUES (?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $nombre);
            if ($stmt->execute()) {
                // Mostrar mensaje de éxito y redirigir
                echo "<script>alert('La pieza se registró correctamente.'); window.location.href = 'lista_piezas.php';</script>";
                exit();
            } else {
                echo "Error al ejecutar la consulta.";
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pieza de un Carro</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Registrar Pieza de un Carro</h1>
            <style>
                .logo-image {
                    width: 80px; /* Ancho deseado */
                    height: auto; /* Mantener la proporción de la imagen */
                    
                    /* Posicionamiento */
                    position: absolute; /* o relative, fixed, dependiendo de lo que necesites */
                    top: 40px; /* Distancia desde la parte superior */
                    left: 1250px; /* Distancia desde la parte izquierda */
                }
            </style>
            <img src="../style/logo.png" alt="Logo" class="logo-image">
        </header>
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <?php if ($role == 'admin'): ?>
                    <li><a href="registrar_solucion.php">Registrar Solución</a></li>
                    <li><a href="lista_soluciones.php">Listas de soluciones</a></li>
                    <li><a href="registrar_problematica.php">Registrar problemática</a></li>
                    <li><a href="lista_problemas.php">Listas de problemas</a></li>
                    <li><a href="registrar_piezas.php" class="btn-action">Registrar piezas de un carro</a></li>
                    <li><a href="lista_piezas.php">Listas de piezas</a></li>
                <?php else: ?>
                    <li><a href="buscar_problematica.php">Buscar problemática</a></li>
                <?php endif; ?>
                <li><a href="../funciones/logout.php">Salir</a></li>
            </ul>
        </nav>
        <main>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Nombre de la Pieza:</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>">
                    <span class="help-block"><?php echo $nombre_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar Pieza</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
