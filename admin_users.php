<?php
$pageTitle = 'Administrar Usuarios';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn || $currentUserRole !== 'admin') {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$stmt = $db->prepare('SELECT id, name, email, role, created_at FROM users WHERE role = \'admin\' ORDER BY created_at DESC');
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <header class="section-header">
        <div>
            <h2>Administrar Usuarios</h2>
            <p>Gestiona los usuarios registrados en el sistema.</p>
        </div>
    </header>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <div class="empty-state">
            <p>No hay usuarios registrados aún.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            <td class="admin-actions">
                                <form action="backend/process_user.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="change_role">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <select name="new_role">
                                        <option value="usuario" <?php echo $user['role'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-secondary">Cambiar Rol</button>
                                </form>
                                <?php if ($user['id'] !== $_SESSION['user_id']): // No permitir borrar a sí mismo ?>
                                    <form action="backend/process_user.php" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn-danger">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>