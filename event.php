<?php
$pageTitle = 'Detalle del Evento - Eventos Locales';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn) {
    header('Location: login.php');
    exit;
}

if (empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$eventId = (int) $_GET['id'];
$db = getDatabaseConnection();
$stmt = $db->prepare('SELECT * FROM events WHERE id = :id');
$stmt->execute([':id' => $eventId]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: index.php');
    exit;
}

$userReservation = false;
if ($isUserLoggedIn && $currentUserRole === 'usuario') {
    $reservationStmt = $db->prepare('SELECT id FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
    $reservationStmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':event_id' => $eventId,
    ]);
    $userReservation = (bool) $reservationStmt->fetch();
}

?>

<section class="page-header">
    <div class="container">
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p class="event-meta"><?php echo htmlspecialchars($event['venue']); ?> · <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></p>
    </div>
</section>

<div class="container" style="margin-bottom: var(--spacingLg);">
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <div class="event-details-card">
        <div class="event-details-content">
            <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

            <div class="event-details-footer">
                <span>Plazas disponibles: <strong><?php echo htmlspecialchars($event['available_seats']); ?></strong></span>
                <?php if ($isUserLoggedIn && $currentUserRole === 'usuario'): ?>
                    <?php if ($userReservation): ?>
                        <a href="dashboard.php" class="btn-secondary">Ya reservaste</a>
                    <?php elseif ($event['available_seats'] > 0): ?>
                        <form action="backend/process_reservation.php" method="POST">
                            <input type="hidden" name="action" value="reserve">
                            <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                            <button type="submit" class="btn-primary">Reservar plaza</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn-secondary" disabled>Evento completo</button>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn-primary">Inicia sesión para reservar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>