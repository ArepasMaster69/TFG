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
                <li><a href="#">Eventos</a></li>
                <li><a href="login.php" class="btn-primary">Iniciar Sesión</a></li>
            </ul>
        </div>
    </nav>
    <main class="app-container">