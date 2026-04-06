<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Event Management Platform'; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="main-nav">
        <div class="container nav-content">
            <div class="logo">
                <h2>Eventos Locales</h2>
            </div>
           <ul class="nav-links">
    <li><a href="index.php">Inicio</a></li>
    
    <?php 
    /* * MOCK DE ESTADO (Para que Samu pueda diseñar).
     * Jorge: Cambia estas variables por tu lógica real de $_SESSION cuando conectes el backend.
     */
    $isUserLoggedIn = true; 
    $currentUserRole = 'admin'; // Valores esperados: 'admin' o 'usuario'
    
    if ($isUserLoggedIn): 
    ?>
        <?php if ($currentUserRole === 'admin'): ?>
            <li><a href="admin.php">Panel Admin</a></li>
        <?php else: ?>
            <li><a href="dashboard.php">Mis Reservas</a></li>
        <?php endif; ?>
        
        <li><a href="backend/process_logout.php" class="btn-primary" style="background-color: var(--colorDanger);">Cerrar Sesión</a></li>
    
    <?php else: ?>
        <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
    <?php endif; ?>
</ul>
        </div>
    </nav>
    <main class="app-container">