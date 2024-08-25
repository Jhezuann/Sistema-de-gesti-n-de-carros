<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Conectar a la base de datos
require '../funciones/conexion.php';

// Variables para almacenar los valores del formulario y los errores
$problema_id = $titulo = $descripcion = '';
$problema_id_err = $titulo_err = $descripcion_err = '';

// Obtener el ID de la solución a editar desde la URL
$solucion_id = $_GET['id'] ?? null;

// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y guardar los datos del formulario
    $problema_id = trim($_POST['problema_id']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    // Validación de ID de problema
    if (empty($problema_id)) {
        $problema_id_err = "Por favor, seleccione un problema.";
    }

    // Validación de título
    if (empty($titulo)) {
        $titulo_err = "Por favor, ingrese un título para la solución.";
    }

    // Validación de descripción
    if (empty($descripcion)) {
        $descripcion_err = "Por favor, ingrese una descripción para la solución.";
    }

    // Si no hay errores, proceder a actualizar en la base de datos
    if (empty($problema_id_err) && empty($titulo_err) && empty($descripcion_err)) {
        $query = "UPDATE soluciones SET problema_id = ?, titulo = ?, descripcion = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("issi", $problema_id, $titulo, $descripcion, $solucion_id);
            if ($stmt->execute()) {
                // Redirigir a la página de lista de soluciones después de editar exitosamente
                $alert_message = "La solución se actualizó correctamente.";
                echo "<script>
                        alert('$alert_message');
                        window.location.href = 'lista_soluciones.php';
                     </script>";
                exit();
            } else {
                echo "Error al ejecutar la consulta.";
            }
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
        
        $stmt->close();
    }
}

// Obtener los datos actuales de la solución para prellenar el formulario
$query_solucion = "SELECT problema_id, titulo, descripcion FROM soluciones WHERE id = ?";
$stmt_solucion = $conn->prepare($query_solucion);

if ($stmt_solucion) {
    $stmt_solucion->bind_param("i", $solucion_id);
    if ($stmt_solucion->execute()) {
        $result_solucion = $stmt_solucion->get_result();
        if ($result_solucion->num_rows == 1) {
            $row = $result_solucion->fetch_assoc();
            $problema_id = $row['problema_id'];
            $titulo = $row['titulo'];
            $descripcion = $row['descripcion'];
        } else {
            echo "No se encontró la solución.";
            exit();
        }
    } else {
        echo "Error al ejecutar la consulta.";
        exit();
    }
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solución</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Editar Solución</h1>
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
                <li><a href="inicio.php"">Inicio</a></li>
                <?php if ($role == 'admin'): ?>
                    <li><a href="registrar_solucion.php">Registrar Solución</a></li>
                    <li><a href="lista_soluciones.php">Listas de soluciones</a></li>
                    <li><a href="registrar_problematica.php">Registrar problemática</a></li>
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $solucion_id; ?>" method="POST">
                <div class="form-group">
                    <label>Problema:</label>
                    <select name="problema_id">
                        <option value="">Seleccionar problema</option>
                        <?php
                        // Obtener lista de problemas de la base de datos
                        $query_problemas = "SELECT id, titulo FROM problemas";
                        $result_problemas = $conn->query($query_problemas);
                        if ($result_problemas->num_rows > 0) {
                            while ($row = $result_problemas->fetch_assoc()) {
                                echo "<option value=\"" . $row['id'] . "\"";
                                if ($problema_id == $row['id']) {
                                    echo " selected";
                                }
                                echo ">" . htmlspecialchars($row['titulo']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="help-block"><?php echo $problema_id_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Solución:</label>
                    <input type="text" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>">
                    <span class="help-block"><?php echo $titulo_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea name="descripcion"><?php echo htmlspecialchars($descripcion); ?></textarea>
                    <span class="help-block"><?php echo $descripcion_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit">Guardar Cambios</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
