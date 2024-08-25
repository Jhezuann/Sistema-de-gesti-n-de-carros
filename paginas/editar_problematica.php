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
$titulo = $descripcion = $parte_id = '';
$titulo_err = $descripcion_err = $parte_id_err = '';

// Obtener el ID de la problemática a editar desde la URL
$problematica_id = $_GET['id'] ?? null;

// Verificar si se ha enviado el formulario de edición o registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y guardar los datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $parte_id = trim($_POST['parte_id']);

    // Validación de título
    if (empty($titulo)) {
        $titulo_err = "Por favor, ingrese un título para la problemática.";
    }

    // Validación de descripción
    if (empty($descripcion)) {
        $descripcion_err = "Por favor, ingrese una descripción para la problemática.";
    }

    // Validación de parte del carro
    if (empty($parte_id)) {
        $parte_id_err = "Por favor, seleccione una parte del carro.";
    }

    // Si no hay errores, proceder a actualizar o insertar en la base de datos
    if (empty($titulo_err) && empty($descripcion_err) && empty($parte_id_err)) {
        if ($problematica_id) {
            // Es una edición
            $query = "UPDATE problemas SET titulo = ?, descripcion = ?, parte_id = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ssii", $titulo, $descripcion, $parte_id, $problematica_id);
                if ($stmt->execute()) {
                    // Mostrar mensaje de éxito y redirigir
                    echo "<script>alert('La problemática se actualizó correctamente.'); window.location.href = 'lista_problemas.php';</script>";
                    exit();
                } else {
                    echo "Error al ejecutar la consulta de actualización.";
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta de actualización: " . $conn->error;
            }
        } else {
            // Es un registro nuevo
            $query = "INSERT INTO problemas (titulo, descripcion, parte_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ssi", $titulo, $descripcion, $parte_id);
                if ($stmt->execute()) {
                    // Mostrar mensaje de éxito y redirigir
                    echo "<script>alert('La problemática se registró correctamente.'); window.location.href = 'lista_problemas.php';</script>";
                    exit();
                } else {
                    echo "Error al ejecutar la consulta de registro.";
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta de registro: " . $conn->error;
            }
        }
    }
}

// Obtener los datos actuales de la problemática para prellenar el formulario si es edición
if ($problematica_id) {
    $query_problematica = "SELECT titulo, descripcion, parte_id FROM problemas WHERE id = ?";
    $stmt_problematica = $conn->prepare($query_problematica);

    if ($stmt_problematica) {
        $stmt_problematica->bind_param("i", $problematica_id);
        if ($stmt_problematica->execute()) {
            $result_problematica = $stmt_problematica->get_result();
            if ($result_problematica->num_rows == 1) {
                $row = $result_problematica->fetch_assoc();
                $titulo = $row['titulo'];
                $descripcion = $row['descripcion'];
                $parte_id = $row['parte_id'];
            } else {
                echo "No se encontró la problemática.";
                exit();
            }
        } else {
            echo "Error al ejecutar la consulta para obtener los datos actuales de la problemática.";
            exit();
        }
        $stmt_problematica->close();
    } else {
        echo "Error en la preparación de la consulta para obtener los datos actuales de la problemática: " . $conn->error;
        exit();
    }
}

// Obtener la lista de partes del carro
$query_partes = "SELECT id, nombre FROM partes_carro";
$result_partes = $conn->query($query_partes);

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $problematica_id ? 'Editar' : 'Registrar'; ?> Problemática</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $problematica_id ? 'Editar' : 'Registrar'; ?> Problemática</h1>
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
                    <li><a href="registrar_piezas.php">Registrar piezas de un carro</a></li>
                    <li><a href="lista_piezas.php">Listas de piezas</a></li>
                <?php else: ?>
                    <li><a href="buscar_problematica.php">Buscar problemática</a></li>
                <?php endif; ?>
                <li><a href="../funciones/logout.php">Salir</a></li>
            </ul>
        </nav>
        <main>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $problematica_id; ?>" method="POST">
                <div class="form-group">
                    <label>Título:</label>
                    <input type="text" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>">
                    <span class="help-block"><?php echo $titulo_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea name="descripcion"><?php echo htmlspecialchars($descripcion); ?></textarea>
                    <span class="help-block"><?php echo $descripcion_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Parte del Carro:</label>
                    <select name="parte_id">
                        <option value="">Seleccionar parte</option>
                        <?php
                        if ($result_partes->num_rows > 0) {
                            while ($row = $result_partes->fetch_assoc()) {
                                echo "<option value=\"" . $row['id'] . "\"";
                                if ($parte_id == $row['id']) {
                                    echo " selected";
                                }
                                echo ">" . htmlspecialchars($row['nombre']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="help-block"><?php echo $parte_id_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit">Guardar Cambios</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
