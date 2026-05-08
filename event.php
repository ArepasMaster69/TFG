<?php
$pageTitle = 'Detalle del Evento - PortalEventos';
require_once 'includes/header.php';
require_once 'config/database.php';

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
if ($isUserLoggedIn) {
    $reservationStmt = $db->prepare('SELECT id FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
    $reservationStmt->execute([':user_id' => $_SESSION['user_id'], ':event_id' => $eventId]);
    $userReservation = (bool) $reservationStmt->fetch();
}
?>

<section style="padding: 4rem 0; background-color: var(--colorCard); border-bottom: 1px solid var(--colorBorder); margin-bottom: var(--spacingLg);">
    <div class="container">
        <h1 style="color: var(--colorPrimary); font-size: clamp(2rem, 3vw, 2.8rem); font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.5px;">
            <?php echo htmlspecialchars($event['title']); ?>
        </h1>
        <p class="event-meta" style="font-size: 1.1rem; display: flex; gap: 0.5rem; align-items: center;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <?php echo htmlspecialchars($event['venue']); ?> &nbsp;&middot;&nbsp; 
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?>
        </p>
    </div>
</section>

<div class="container" style="margin-bottom: var(--spacingLg); max-width: 800px;">
    <div style="background-color: var(--colorCard); border-radius: var(--borderRadius); padding: 2.5rem; box-shadow: var(--shadowCard); border: 1px solid var(--colorBorder);">
        <div class="event-details-content">
            
            <div class="rich-text-content" style="color: var(--colorTextLight); line-height: 1.8; font-size: 1.05rem; margin-bottom: 2.5rem;">
                <?php echo $event['description']; ?>
            </div>

            <div class="event-details-footer" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid var(--colorBorder); margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
                <span style="color: var(--colorTextLight);">Plazas disponibles: <strong style="color: var(--colorBrand); font-size: 1.2rem;"><?php echo htmlspecialchars($event['available_seats']); ?></strong></span>
                
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <?php if ($isUserLoggedIn): ?>
                        
                        <?php if ($userReservation): ?>
                            <button type="button" class="btn-secondary" disabled style="opacity: 0.7; cursor: not-allowed;">Ya tienes plaza(s)</button>
                        <?php elseif ($event['available_seats'] > 0): ?>
                            <form action="backend/process_reservation.php" method="POST" style="margin: 0; display: flex; gap: 0.5rem; align-items: center;">
                                <input type="hidden" name="action" value="reserve">
                                <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                <input type="number" name="seats" value="1" min="1" max="<?php echo $event['available_seats']; ?>" style="width: 70px; padding: 0.4rem; border-radius: 6px; border: 1px solid var(--colorBorder); background: var(--colorBackground); color: var(--colorText); outline: none;">
                                <button type="submit" class="btn-primary">Reservar</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn-secondary" disabled style="color: var(--colorDanger); border-color: var(--colorDangerBg);">Agotado</button>
                        <?php endif; ?>

                        <?php if ($currentUserRole === 'admin'): ?>
                            <a href="admin_event_form.php?action=edit&id=<?php echo $eventId; ?>" class="btn-secondary">Editar</a>
                        <?php endif; ?>

                    <?php else: ?>
                        <a href="login.php" class="btn-primary">Inicia sesión para reservar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>