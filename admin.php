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
                                
                                <form id="deleteForm_<?php echo $event['id']; ?>" action="backend/process_event.php" method="POST">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="button" class="btn-danger" onclick="openDeleteModal('deleteForm_<?php echo $event['id']; ?>')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este evento? Esta acción borrará permanentemente los datos y las reservas asociadas.</p>
        </div>
        <div class="modal-footer form-actions">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <button type="button" class="btn-danger" id="confirmDeleteBtn">Sí, eliminar</button>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>