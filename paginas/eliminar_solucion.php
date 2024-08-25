<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Verificar si se ha enviado el ID de la solución a eliminar por GET
$solucion_id = $_GET['id'] ?? null;

// Si no se proporciona un ID de solución válido, redirigir
if (!$solucion_id) {
    echo "<script>alert('La solución se elimino exitosamente.'); window.location.href = 'lista_soluciones.php';</script>";
    exit();
}

// Conectar a la base de datos
require '../funciones/conexion.php';

// Preparar y ejecutar la eliminación de la solución
$query = "DELETE FROM soluciones WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $solucion_id);
    if ($stmt->execute()) {
        // Redirigir a la página de lista de soluciones después de eliminar exitosamente
        echo "<script>alert('La solución se elimino exitosamente.'); window.location.href = 'lista_soluciones.php';</script>";
        exit();
    } else {
        echo "Error al ejecutar la consulta de eliminación.";
    }
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
