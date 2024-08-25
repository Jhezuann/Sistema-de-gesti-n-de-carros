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

// Obtener el ID de la pieza a eliminar desde la URL
$pieza_id = $_GET['id'] ?? null;

// Verificar si se ha enviado la confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la eliminación de la pieza
    $query = "DELETE FROM partes_carro WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $pieza_id);
        if ($stmt->execute()) {
            // Redirigir a la página de lista de piezas después de eliminar exitosamente
            echo "<script>alert('La pieza se Elimino exitosamente.'); window.location.href = 'lista_piezas.php';</script>";
            exit();
        } else {
            echo "<script>alert('Esta pieza no se puede eliminar ya que esta unida a una problematica, primero elimine la problematica.');</script>";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    
    $stmt->close();
}

// Obtener el nombre de la pieza para mostrar en el mensaje de confirmación
$query_nombre_pieza = "SELECT nombre FROM partes_carro WHERE id = ?";
$stmt_nombre_pieza = $conn->prepare($query_nombre_pieza);

if ($stmt_nombre_pieza) {
    $stmt_nombre_pieza->bind_param("i", $pieza_id);
    if ($stmt_nombre_pieza->execute()) {
        $result_nombre_pieza = $stmt_nombre_pieza->get_result();
        if ($result_nombre_pieza->num_rows == 1) {
            $row = $result_nombre_pieza->fetch_assoc();
            $nombre_pieza = $row['nombre'];
        } else {
            echo "No se encontró la pieza.";
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

// Cerrar conexión
$stmt_nombre_pieza->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Pieza</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Eliminar Pieza</h1>
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
                    <li><a href="lista_soluciones.php">Lista de Soluciones</a></li>
                    <li><a href="registrar_piezas.php">Registrar Piezas</a></li>
                    <li><a href="registrar_problematica.php">Registrar Problemática</a></li>
                <?php else: ?>
                    <li><a href="buscar_problematica.php">Buscar Problemática</a></li>
                <?php endif; ?>
                <li><a href="../funciones/logout.php">Salir</a></li>
            </ul>
        </nav>
        <main>
            <div class="confirm-delete">
                <h2>Confirmar Eliminación de Pieza</h2>
                <p>¿Está seguro que desea eliminar la pieza "<strong><?php echo htmlspecialchars($nombre_pieza); ?></strong>"?</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $pieza_id; ?>" method="POST">
                    <button type="submit">Eliminar</button>
                    <a href="lista_piezas.php" class="cancel-btn">Cancelar</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
