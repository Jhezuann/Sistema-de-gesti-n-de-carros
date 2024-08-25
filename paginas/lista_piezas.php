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

// Obtener la lista de piezas (partes) desde la base de datos
$query = "SELECT id, nombre FROM partes_carro";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
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
    <title>Lista de Piezas</title>
    <link rel="stylesheet" href="../style/styles.css">
    <style>
        .action-links {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre los enlaces */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Lista de Piezas</h1>
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
                    <li><a href="lista_piezas.php" class="btn-action">Listas de piezas</a></li>
                <?php else: ?>
                    <li><a href="buscar_problematica.php">Buscar problemática</a></li>
                <?php endif; ?>
                <li><a href="../funciones/logout.php">Salir</a></li>
            </ul>
        </nav>
        <main>
            <div class="solution-list">
                <h2>Piezas Disponibles</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <?php if ($role == 'admin'): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <?php if ($role == 'admin'): ?>
                                    <td class="action-links">
                                        <a href="editar_pieza.php?id=<?php echo $row['id']; ?>" class="btn-action">Editar</a>
                                        <a href="eliminar_pieza.php?id=<?php echo $row['id']; ?>" class="btn-action">Eliminar</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
