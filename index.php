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
        <span class="eyebrow">Plataforma Ciudadana</span>
        
        <?php if ($isUserLoggedIn): ?>
            <h1>Gestiona y reserva actividades en tu municipio</h1>
            <p>El portal oficial para descubrir eventos culturales, talleres y conciertos de forma accesible y controlada.</p>
        <?php else: ?>
            <h1>Descubre y reserva las mejores actividades culturales</h1>
            <p>Conectamos a los ayuntamientos y ciudadanos en un solo lugar. Regístrate gratis para asegurar tu plaza en eventos exclusivos.</p>
            <div class="hero-actions">
                <a href="register.php" class="btn-primary" style="background-color: #ffffff; color: var(--colorBrand);">Crear cuenta</a>
                <a href="login.php" class="btn-primary" style="background-color: transparent; border: 1px solid rgba(255,255,255,0.5);">Iniciar sesión</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="container" style="margin-bottom: var(--spacingLg);">
    <header class="section-header" style="align-items: center;">
        <div>
            <h2>Eventos próximos</h2>
            <p>Explora las actividades programadas en tu zona.</p>
        </div>
        
        <div class="search-box">
            <input type="text" id="eventSearch" placeholder="Buscar por título o palabra clave...">
        </div>
    </header>

    <div class="events-grid" id="eventsContainer">
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3>No hay eventos programados</h3>
                <p>Actualmente no tenemos actividades disponibles. Vuelve a revisar pronto para descubrir nuevas propuestas.</p>
            </div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <article class="event-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <span class="pill">Próximamente</span>
                    </div>
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                    
                    <p class="event-desc" style="color: var(--colorTextLight); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <?php echo htmlspecialchars(substr(strip_tags($event['description']), 0, 100)); ?>...
                    </p>
                    
                    <div class="card-footer">
                        <span style="font-weight: 700; color: var(--colorBrand);"><?php echo (int) $event['available_seats']; ?> plazas libres</span>
                        <a href="event.php?id=<?php echo $event['id']; ?>" class="btn-secondary">Ver detalles</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script src="js/main.js"></script>

<?php require_once 'includes/footer.php'; ?>