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
    <title>Detalles de los Neumáticos</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalles de los Neumáticos</h1>
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
            <div class="tire-details">
                <h2>Información de los Neumáticos</h2>
                <p>
                    <strong>Descripción:</strong> Los neumáticos son un componente vital del vehículo, ya que son el único punto de contacto con la carretera. Proporcionan tracción, estabilidad y comodidad de manejo.
                </p>
                <p>
                    <strong>Especificaciones Técnicas:</strong>
                    <ul>
                        <li>Tipo: Radiales</li>
                        <li>Material: Caucho con refuerzos de acero</li>
                        <li>Tamaño: 205/55 R16</li>
                        <li>Índice de carga: 91 (615 kg por neumático)</li>
                        <li>Índice de velocidad: V (hasta 240 km/h)</li>
                        <li>Profundidad del dibujo: 8 mm (nuevo)</li>
                    </ul>
                </p>
                <p>
                    <strong>Mantenimiento Recomendado:</strong>
                    <ul>
                        <li>Revisión de la presión: Cada mes</li>
                        <li>Rotación de neumáticos: Cada 10,000 km</li>
                        <li>Alineación y balanceo: Cada 20,000 km</li>
                        <li>Reemplazo de neumáticos: Cada 40,000 - 50,000 km, o cuando la profundidad del dibujo sea inferior a 2 mm</li>
                    </ul>
                </p>
                <img src="../style/cauchos.jpg" alt="Imagen de los Neumáticos" style="width:100%; height:auto; border-radius: 8px; margin-bottom: 20px;">
                
            </div>
        </main>
    </div>
</body>
</html>
