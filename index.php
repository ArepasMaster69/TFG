<?php
/**
 * Main entry point for the Event Management Platform.
 * Initializes the application and renders the base layout.
 */

// require_once 'config/database.php'; // Lo descomentaremos cuando empecemos con el backend

$pageTitle = 'Event Management Platform - TFG';
$systemStatus = 'System architecture initialized successfully. Ready for development.';
$isSystemActive = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="app-container">
        <header class="app-header">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        </header>
        
        <section class="dashboard-preview">
            <?php if ($isSystemActive): ?>
                <div class="status-message is-success">
                    <p><?php echo htmlspecialchars($systemStatus); ?></p>
                    <p>Repository configured. Waiting for the first feature branch...</p>
                </div>
            <?php else: ?>
                <div class="status-message is-error">
                    <p>System offline.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script src="js/main.js"></script>
</body>
</html>