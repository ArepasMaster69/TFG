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

$resLabelStmt = $db->prepare('SELECT e.id, e.title, e.venue, e.event_date, e.available_seats, r.id AS reservation_id FROM reservations r JOIN events e ON e.id = r.event_id WHERE r.user_id = :user_id ORDER BY e.event_date ASC');
$resLabelStmt->execute([':user_id' => $userId]);
$reservations = $resLabelStmt->fetchAll();

$eventsStmt = $db->prepare('SELECT * FROM events WHERE event_date >= NOW() AND id NOT IN (SELECT event_id FROM reservations WHERE user_id = :user_id) ORDER BY event_date ASC');
$eventsStmt->execute([':user_id' => $userId]);
$events = $eventsStmt->fetchAll();
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <header class="section-header">
        <div>
            <h2>Hola, <?php echo $userName; ?></h2>
            <p>Estas son tus reservas y los próximos eventos disponibles.</p>
        </div>
    </header>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <section class="dashboard-section">
        <h3>Mis reservas</h3>
        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <p>Aún no tienes reservas. Explora eventos disponibles y reserva tu plaza.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($reservations as $event): ?>
                    <article class="event-card">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                        <div class="card-footer">
                            <span class="pill">Reservado</span>
                            <form action="backend/process_reservation.php" method="POST">
                                <input type="hidden" name="action" value="cancel">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn-secondary">Cancelar reserva</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="dashboard-section">
        <h3>Eventos disponibles</h3>
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <p>No hay eventos disponibles por ahora. Vuelve pronto.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <article class="event-card">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
                        <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                        <div class="card-footer">
                            <span class="pill"><?php echo (int) $event['available_seats']; ?> plazas libres</span>
                            <form action="backend/process_reservation.php" method="POST">
                                <input type="hidden" name="action" value="reserve">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn-primary">Reservar</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>