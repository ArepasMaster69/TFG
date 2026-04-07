<?php
$pageTitle = 'Panel de Administración';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn || $currentUserRole !== 'admin') {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$stmt = $db->prepare('SELECT * FROM events ORDER BY event_date ASC');
$stmt->execute();
$events = $stmt->fetchAll();
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <header class="section-header">
        <div>
            <h2>Panel de administración</h2>
            <p>Gestiona los eventos activos del municipio desde esta sección.</p>
        </div>
        <div>
            <a href="admin_event_form.php?action=create" class="btn-primary">+ Nuevo evento</a>
            <a href="admin_users.php" class="btn-secondary">Administrar Usuarios</a>
        </div>
    </header>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($events)): ?>
        <div class="empty-state">
            <p>No hay eventos registrados aún. Comienza creando uno nuevo.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Plazas</th>
                        <th>Disponibles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($event['event_date'])); ?></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td><?php echo (int) $event['total_seats']; ?></td>
                            <td><?php echo (int) $event['available_seats']; ?></td>
                            <td class="admin-actions">
                                <a href="admin_event_form.php?action=edit&id=<?php echo $event['id']; ?>" class="btn-secondary">Editar</a>
                                <form action="backend/process_event.php" method="POST" onsubmit="return confirm('¿Eliminar este evento?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" class="btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>