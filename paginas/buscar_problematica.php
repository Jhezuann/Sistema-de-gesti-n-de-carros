<?php
session_start();
require '../funciones/conexion.php'; // Archivo que contiene la conexión a la base de datos

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = $_SESSION['role'];

// Variables para almacenar el término de búsqueda y el resultado de la búsqueda
$termino = '';
$resultados = [];

// Función para obtener las soluciones asociadas a un problema
function obtenerSoluciones($problema_id, $conn) {
    $query = "SELECT * FROM soluciones WHERE problema_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $problema_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close(); // Cerrar la declaración preparada después de obtener el resultado
    return $result;
}

// Procesar el formulario de búsqueda al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $termino = trim($_GET['query']);

    // Asegurar que el término tenga comodines
    $termino = "%$termino%";

    // Dividir el término en palabras clave
    $keywords = explode(" ", $termino);

    // Realizar la búsqueda en la base de datos
    $query = "SELECT * FROM problemas WHERE ";
    $conditions = [];
    $params = [];

    foreach ($keywords as $index => $keyword) {
        $param = "keyword" . $index;
        $conditions[] = "(titulo LIKE ? OR descripcion LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }

    $query .= implode(" OR ", $conditions);

    $stmt = $conn->prepare($query);

    // Bind de parámetros
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    // Almacenar los resultados en un arreglo
    while ($row = $result->fetch_assoc()) {
        $resultados[] = $row;
    }

    $stmt->close(); // Cerrar la declaración preparada
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Problemática</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Buscar Problemática</h1>
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
                    <li><a href="buscar_problematica.php" class="btn-action">Buscar problemática</a></li>
                <?php endif; ?>
                <li><a href="../funciones/logout.php">Salir</a></li>
            </ul>
        </nav>
        <main>
            <div class="user-search">
                <h2>Buscador de Problemáticas</h2>
                <p>Utiliza el buscador para encontrar problemáticas de carros.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <input type="text" name="query" placeholder="Buscar problemática..." value="<?php echo htmlspecialchars($termino); ?>" required>
                    <br>
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <?php if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])): ?>
                <h2>Resultados de la búsqueda:</h2>
                <?php if (empty($resultados)): ?>
                    <p>No se encontraron problemáticas que coincidan con el término "<?php echo htmlspecialchars($termino); ?>".</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($resultados as $problema): ?>
                            <li>
                                <h3><?php echo htmlspecialchars($problema['titulo']); ?></h3>
                                <p><?php echo htmlspecialchars($problema['descripcion']); ?></p>
                                <?php
                                // Obtener soluciones asociadas a este problema
                                $soluciones = obtenerSoluciones($problema['id'], $conn);
                                if (!empty($soluciones)) {
                                    echo "<h4>Soluciones:</h4>";
                                    echo "<ul>";
                                    foreach ($soluciones as $solucion) {
                                        echo "<li>";
                                        echo "<p><strong>Título:</strong> " . htmlspecialchars($solucion['titulo']) . "</p>";
                                        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($solucion['descripcion']) . "</p>";
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "<p>No hay soluciones registradas para este problema.</p>";
                                }
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
