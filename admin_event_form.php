<?php
$pageTitle = 'Administración de Eventos - Eventos Locales';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn || $currentUserRole !== 'admin') {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$action = $_GET['action'] ?? 'create';
$event = [
    'title' => '',
    'description' => '',
    'venue' => '',
    'event_date' => '',
    'total_seats' => '',
];

if ($action === 'edit' && !empty($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM events WHERE id = :id');
    $stmt->execute([':id' => (int) $_GET['id']]);
    $event = $stmt->fetch();

    if (!$event) {
        header('Location: admin.php?error=' . urlencode('Evento no encontrado.'));
        exit;
    }
}

$formTitle = $action === 'edit' ? 'Editar evento' : 'Crear nuevo evento';
$submitLabel = $action === 'edit' ? 'Guardar cambios' : 'Publicar evento';

$eventDateValue = '';
if (!empty($event['event_date'])) {
    $eventDateValue = date('Y-m-d\TH:i', strtotime($event['event_date']));
}
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <section class="auth-card form-card">
        <h2><?php echo $formTitle; ?></h2>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form action="backend/process_event.php" method="POST" class="form-layout">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="event_id" value="<?php echo (int) $event['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="title">Título del evento</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="venue">Lugar</label>
                <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
            </div>

            <div class="form-group">
                <label for="event_date">Fecha y hora</label>
                <input type="datetime-local" id="event_date" name="event_date" value="<?php echo $eventDateValue; ?>" required>
            </div>

            <div class="form-group">
                <label for="total_seats">Plazas totales</label>
                <input type="number" id="total_seats" name="total_seats" min="1" value="<?php echo htmlspecialchars($event['total_seats']); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><?php echo $submitLabel; ?></button>
                <a href="admin.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>