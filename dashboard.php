<?php
$pageTitle = 'Mis Reservas y Eventos';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['user_name'] ?? 'Usuario');

// Modificado para recuperar el número de plazas (r.seats)
$resLabelStmt = $db->prepare('SELECT e.id, e.title, e.venue, e.event_date, e.available_seats, r.id AS reservation_id, r.seats FROM reservations r JOIN events e ON e.id = r.event_id WHERE r.user_id = :user_id ORDER BY e.event_date ASC');
$resLabelStmt->execute([':user_id' => $userId]);
$reservations = $resLabelStmt->fetchAll();

$eventsStmt = $db->prepare('SELECT * FROM events WHERE event_date >= NOW() AND id NOT IN (SELECT event_id FROM reservations WHERE user_id = :user_id) ORDER BY event_date ASC');
$eventsStmt->execute([':user_id' => $userId]);
$events = $eventsStmt->fetchAll();
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    
    <header class="section-header" style="border-bottom: 1px solid var(--colorBorder); padding-bottom: 1.5rem; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--colorPrimary); letter-spacing: -0.5px;">Hola, <?php echo $userName; ?></h2>
            <p style="color: var(--colorTextLight); font-size: 1.1rem;">Gestiona tus entradas y descubre nuevas actividades en tu municipio.</p>
        </div>
    </header>

    <section style="margin-bottom: 4rem;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--colorPrimary);">
            <svg width="24" height="24" fill="none" stroke="var(--colorBrand)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            Mis reservas activas
        </h3>
        
        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <div class="empty-state-icon success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Aún no tienes reservas</h3>
                <p>No te has apuntado a ningún evento todavía. Explora la lista de abajo y asegura tu plaza.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($reservations as $event): ?>
                    <article class="event-card" style="border: 1px solid var(--colorSuccessBg);">
                        <div style="margin-bottom: 1rem;">
                            <span class="pill success">✓ Confirmado (<?php echo $event['seats']; ?> plazas)</span>
                        </div>
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                        <p class="event-desc">Tu plaza está asegurada. Recuerda llegar 15 minutos antes del inicio del evento.</p>
                        
                        <div class="card-footer">
                            <span style="color: var(--colorTextLight); font-size: 0.85rem;">Ref: #<?php echo str_pad($event['reservation_id'], 5, '0', STR_PAD_LEFT); ?></span>
                            <form action="backend/process_reservation.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="cancel">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Cancelar reserva</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section>
        <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--colorPrimary);">
            <svg width="24" height="24" fill="none" stroke="var(--colorTextLight)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Sugerencias para ti
        </h3>
        
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>No hay más eventos</h3>
                <p>Has reservado todos los eventos disponibles o no hay nuevas actividades programadas por el ayuntamiento en este momento.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <article class="event-card">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                        <p class="event-desc"><?php echo htmlspecialchars(substr(strip_tags($event['description']), 0, 100)); ?>...</p>
                        
                        <div class="card-footer">
                            <span style="font-weight: 700; color: var(--colorBrand);"><?php echo (int) $event['available_seats']; ?> plazas libres</span>
                            <a href="event.php?id=<?php echo $event['id']; ?>" class="btn-primary" style="padding: 0.4rem 1rem;">Ver detalles</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>