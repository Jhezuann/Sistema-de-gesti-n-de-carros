<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Verificar si el usuario tiene permisos para eliminar (solo admin)
if ($role !== 'admin') {
    header("Location: inicio.php");
    exit();
}

// Conectar a la base de datos
require '../funciones/conexion.php';

// Obtener el ID de la problemática a eliminar desde la URL
$problematica_id = $_GET['id'] ?? null;

if ($problematica_id) {
    // Eliminar la problemática de la base de datos
    $query = "DELETE FROM problemas WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $problematica_id);
        if ($stmt->execute()) {
            echo "<script>alert('La problematica se elimino exitosamente.'); window.location.href = 'lista_problemas.php';</script>";
            exit();
        } else {
            echo "<script>alert('La problematica no se puede eliminar ya que una solución depende de esta problematica, primero elimine la solución.'); window.location.href = 'lista_problemas.php';</script>";
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "ID de problemática no válido.";
}

// Cerrar conexión
$conn->close();
?>
