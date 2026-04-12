<?php
$pageTitle = 'Inicio - Eventos Locales';
require_once 'includes/header.php';
require_once 'config/database.php';

$db = getDatabaseConnection();
$stmt = $db->prepare('SELECT * FROM events WHERE event_date >= NOW() ORDER BY event_date ASC LIMIT 6');
$stmt->execute();
$events = $stmt->fetchAll();
?>

<section class="hero">
    <div class="container hero-content">
        <div>
            <span class="eyebrow">Eventos locales</span>
            <?php if ($isUserLoggedIn): ?>
                <h1>Gestiona y reserva actividades culturales de tu zona.</h1>
                <p>Una plataforma pensada para ayuntamientos, asociaciones y centros culturales que quieren ofrecer eventos accesibles y controlados.</p>
            <?php else: ?>
                <h1>Descubre y reserva actividades culturales de tu zona.</h1>
                <p>Regístrate para acceder a eventos exclusivos y reservar tu plaza.</p>
                <div class="hero-actions">
                    <a href="register.php" class="btn-primary" style="background-color: #ffffff; color: #2563eb;">Crear cuenta</a>
                    <a href="login.php" class="btn-secondary" style="border-color: #ffffff; color: #ffffff;">Iniciar sesión</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container" style="margin-bottom: var(--spacingLg);">
    <header class="section-header">
        <h2>Eventos próximos</h2>
        <p>Descubre las actividades y asegura tu plaza.</p>
    </header>

    <div class="events-grid">
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <p>No hay eventos programados por ahora. Vuelve más tarde para ver nuevas actividades.</p>
            </div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <article class="event-card">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                    <p><?php echo htmlspecialchars(substr(strip_tags($event['description']), 0, 100)); ?>...</p>
                    <div class="card-footer">
                        <span class="pill"><?php echo (int) $event['available_seats']; ?> plazas libres</span>
                        <a href="event.php?id=<?php echo $event['id']; ?>" class="btn-secondary">Ver detalles</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>