<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario de la sesión
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Motor</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalles del Motor</h1>
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
            <div class="motor-details">
                <h2>Información del Motor</h2>
                <p>
                    <strong>Descripción:</strong> El motor de combustión interna es un tipo de máquina que obtiene energía mecánica directamente de la energía química generada por un combustible que arde dentro de la cámara de combustión. Este motor es el corazón del vehículo, responsable de convertir el combustible en movimiento.
                </p>
                <p>
                    <strong>Especificaciones Técnicas:</strong>
                    <ul>
                        <li>Tipo: Motor de combustión interna de cuatro tiempos</li>
                        <li>Desplazamiento: 2.0 litros</li>
                        <li>Configuración: En línea de cuatro cilindros</li>
                        <li>Potencia: 150 HP a 6,000 RPM</li>
                        <li>Torque: 200 Nm a 4,000 RPM</li>
                        <li>Sistema de Combustible: Inyección electrónica</li>
                        <li>Refrigeración: Líquida</li>
                    </ul>
                </p>
                <p>
                    <strong>Mantenimiento Recomendado:</strong> 
                    <ul>
                        <li>Cambio de aceite: Cada 5,000 km o 6 meses</li>
                        <li>Reemplazo del filtro de aire: Cada 10,000 km</li>
                        <li>Revisión del sistema de enfriamiento: Cada 20,000 km</li>
                        <li>Ajuste de válvulas: Cada 30,000 km</li>
                    </ul>
                </p>
                <img src="../style/motor.jpg" alt="Imagen del Motor" style="width:100%; height:auto; border-radius: 8px; margin-bottom: 20px;">
            </div>
        </main>
    </div>
</body>
</html>
