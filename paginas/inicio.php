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
    <title>Página Protegida</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenido a tu panel</h1>
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
                <li><a href="inicio.php" class="btn-action">Inicio</a></li>
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
            <?php if ($role == 'admin'): ?>
                <div class="admin-panel">
                    <h2>Panel de Administración</h2>
                    <p>Bienvenido al panel de administración. Aquí puedes gestionar las piezas de carros, las problemáticas y soluciones.</p>
                    <div class="image-container">
                        <div class="image">
                            <img src="../style/motor.jpg" alt="Motor">
                            <a href="motor.php" class="btn-ver-mas">Ver más</a>
                        </div>
                        <div class="image">
                            <img src="../style/frenos.jpg" alt="Frenos">
                            <a href="frenos.php" class="btn-ver-mas">Ver más</a>
                        </div>
                        <div class="image">
                            <img src="../style/cauchos.jpg" alt="Neumáticos">
                            <a href="neumaticos.php" class="btn-ver-mas">Ver más</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>


                <div class="admin-panel">
                    <div class="user-search">
                        <h2>Buscador de Problemáticas</h2>
                        <p>Utiliza el buscador para encontrar soluciones a problemáticas de carros.</p>
                        <form action="buscar_problematica.php" method="GET">
                            <input type="text" name="query" placeholder="Buscar problemática..." required>
                            <br>
                            <button type="submit">Buscar</button>
                        </form>
                    </div>
                    <div class="image-container">
                        <div class="image">
                            <img src="../style/motor.jpg" alt="Motor">
                            <a href="motor.php" class="btn-ver-mas">Ver más</a>
                        </div>
                        <div class="image">
                            <img src="../style/frenos.jpg" alt="Frenos">
                            <a href="frenos.php" class="btn-ver-mas">Ver más</a>
                        </div>
                        <div class="image">
                            <img src="../style/cauchos.jpg" alt="Neumáticos">
                            <a href="neumaticos.php" class="btn-ver-mas">Ver más</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
