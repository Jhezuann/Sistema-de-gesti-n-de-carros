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
$titulo = $descripcion = $parte_id = '';
$titulo_err = $descripcion_err = $parte_id_err = '';

// Obtener lista de partes del carro para el dropdown
$partes_carro = [];
$query_partes = "SELECT id, nombre FROM partes_carro";
$result_partes = $conn->query($query_partes);
if ($result_partes->num_rows > 0) {
    while ($row = $result_partes->fetch_assoc()) {
        $partes_carro[] = $row;
    }
}

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y guardar los datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $parte_id = $_POST['parte_id'];

    // Validación de título
    if (empty($titulo)) {
        $titulo_err = "Por favor, ingrese un título.";
    }

    // Validación de descripción
    if (empty($descripcion)) {
        $descripcion_err = "Por favor, ingrese una descripción.";
    }

    // Validación de parte del carro
    if (empty($parte_id)) {
        $parte_id_err = "Por favor, seleccione una parte del carro.";
    }

    // Si no hay errores, proceder a insertar en la base de datos
    if (empty($titulo_err) && empty($descripcion_err) && empty($parte_id_err)) {
        $query = "INSERT INTO problemas (titulo, descripcion, parte_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssi", $titulo, $descripcion, $parte_id);
            if ($stmt->execute()) {
                // Mostrar mensaje de éxito y redirigir
                echo "<script>alert('La problemática se registró correctamente.'); window.location.href = 'lista_problemas.php';</script>";
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
    <title>Registrar Problemática</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Registrar Problemática</h1>
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
                    <li><a href="registrar_problematica.php" class="btn-action">Registrar problemática</a></li>
                    <li><a href="lista_problemas.php">Listas de problemas</a></li>
                    <li><a href="registrar_piezas.php">Registrar piezas de un carro</a></li>
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
                    <label>Problemática:</label>
                    <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($titulo); ?>">
                    <span class="help-block"><?php echo $titulo_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($descripcion); ?></textarea>
                    <span class="help-block"><?php echo $descripcion_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Parte del Carro:</label>
                    <select name="parte_id" class="form-control">
                        <option value="">Seleccione una parte</option>
                        <?php foreach ($partes_carro as $parte): ?>
                            <option value="<?php echo $parte['id']; ?>" <?php if ($parte_id == $parte['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($parte['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-block"><?php echo $parte_id_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
