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
    <title>Detalles de los Frenos</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalles de los Frenos</h1>
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
            <div class="brake-details">
                <h2>Información de los Frenos</h2>
                <p>
                    <strong>Descripción:</strong> El sistema de frenos es fundamental para la seguridad del vehículo. Este sistema permite reducir la velocidad y detener el automóvil mediante la fricción entre los componentes del sistema de frenos y las ruedas.
                </p>
                <p>
                    <strong>Especificaciones Técnicas:</strong>
                    <ul>
                        <li>Tipo: Frenos de disco en las ruedas delanteras y traseras</li>
                        <li>Material de los discos: Aleación de hierro fundido</li>
                        <li>Diámetro del disco delantero: 320 mm</li>
                        <li>Diámetro del disco trasero: 300 mm</li>
                        <li>Tipo de pastillas: Orgánicas</li>
                        <li>Sistema de asistencia: ABS (Sistema antibloqueo de frenos)</li>
                    </ul>
                </p>
                <p>
                    <strong>Mantenimiento Recomendado:</strong>
                    <ul>
                        <li>Revisión de pastillas de freno: Cada 10,000 km</li>
                        <li>Reemplazo de discos de freno: Cada 50,000 km</li>
                        <li>Cambio de líquido de frenos: Cada 30,000 km</li>
                        <li>Revisión del sistema ABS: Anualmente</li>
                    </ul>
                </p>
                <img src="../style/frenos.jpg" alt="Imagen de los Frenos" style="width:100%; height:auto; border-radius: 8px; margin-bottom: 20px;">

            </div>
        </main>
    </div>
</body>
</html>
