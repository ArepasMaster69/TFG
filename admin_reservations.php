<?php
$pageTitle = 'Lista de Reservas - PortalEventos';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn || $currentUserRole !== 'admin') {
    header('Location: login.php');
    exit;
}

if (empty($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$eventId = (int) $_GET['id'];
$db = getDatabaseConnection();

$eventStmt = $db->prepare('SELECT title, event_date, total_seats, available_seats FROM events WHERE id = :id');
$eventStmt->execute([':id' => $eventId]);
$event = $eventStmt->fetch();

if (!$event) {
    header('Location: admin.php?error=' . urlencode('Evento no encontrado.'));
    exit;
}

$resStmt = $db->prepare('
    SELECT r.id, r.seats, r.reserved_at, u.name, u.email 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.event_id = :event_id 
    ORDER BY r.reserved_at DESC
');
$resStmt->execute([':event_id' => $eventId]);
$reservations = $resStmt->fetchAll();

$plazasOcupadas = $event['total_seats'] - $event['available_seats'];
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <header class="section-header" style="border-bottom: 1px solid var(--colorBorder); padding-bottom: 1.5rem; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.8rem; color: var(--colorPrimary);">
                Reservas: <span style="color: var(--colorBrand);"><?php echo htmlspecialchars($event['title']); ?></span>
            </h2>
            <p style="color: var(--colorTextLight);">
                Fecha: <?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?> &middot; 
                Ocupación: <?php echo $plazasOcupadas; ?> / <?php echo $event['total_seats']; ?> plazas
            </p>
        </div>
        <div>
            <a href="admin.php" class="btn-secondary">Volver a Eventos</a>
        </div>
    </header>

    <?php if (empty($reservations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3>Sin reservas todavía</h3>
            <p>Ningún ciudadano se ha apuntado a este evento por el momento.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Ciudadano</th>
                        <th>Email</th>
                        <th>Plazas Reservadas</th>
                        <th>Fecha de la Reserva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--colorPrimary);"><?php echo htmlspecialchars($res['name']); ?></td>
                            <td><?php echo htmlspecialchars($res['email']); ?></td>
                            <td>
                                <span class="pill success"><?php echo $res['seats']; ?> plaza(s)</span>
                            </td>
                            <td style="color: var(--colorTextLight); font-size: 0.9rem;">
                                <?php echo date('d/m/Y H:i', strtotime($res['reserved_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>