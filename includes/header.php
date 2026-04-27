<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isUserLoggedIn = isset($_SESSION['user_id']);
$currentUserRole = $_SESSION['user_role'] ?? 'usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Civitas - Plataforma Ciudadana'; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="main-nav">
        <div class="container nav-content">
            <div class="logo">
                <a href="index.php" style="text-decoration: none;">
                    <h2>PortalEventos</h2> </a>
            </div>
            
            <ul class="nav-links">
                <?php if ($isUserLoggedIn): ?>
                    <?php if ($currentUserRole === 'admin'): ?>
                        <li><a href="admin.php">Panel Admin</a></li>
                    <?php else: ?>
                        <li><a href="dashboard.php">Mis Reservas</a></li>
                    <?php endif; ?>
                    
                    <li><a href="backend/process_logout.php" class="btn-primary btn-danger">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="app-container">