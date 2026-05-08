<?php
$pageTitle = 'Administrar Usuarios';
require_once 'includes/header.php';
require_once 'config/database.php';

if (!$isUserLoggedIn || $currentUserRole !== 'admin') {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
// Muestra todos los usuarios (no solo los admins)
$stmt = $db->prepare('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC');
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="container" style="margin-top: var(--spacingLg); margin-bottom: var(--spacingLg);">
    <header class="section-header">
        <div>
            <h2>Administrar Usuarios</h2>
            <p>Gestiona los roles y elimina cuentas del sistema.</p>
        </div>
        <div>
            <a href="admin.php" class="btn-secondary">Volver a Eventos</a>
        </div>
    </header>

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
                            <td>
                                <span class="pill <?php echo $user['role'] === 'admin' ? 'success' : ''; ?>">
                                    <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            <td class="admin-actions">
                                <form action="backend/process_user.php" method="POST" style="display: inline; margin: 0;">
                                    <input type="hidden" name="action" value="change_role">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <select name="new_role" style="padding: 0.4rem; border-radius: 6px; border: 1px solid var(--colorBorder); background: var(--colorBackground); color: var(--colorText); outline: none;">
                                        <option value="usuario" <?php echo $user['role'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-secondary" style="padding: 0.4rem 0.8rem;">Cambiar</button>
                                </form>
                                
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <form id="deleteUserForm_<?php echo $user['id']; ?>" action="backend/process_user.php" method="POST" style="display: inline; margin: 0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="button" class="btn-danger" style="padding: 0.4rem 0.8rem;" onclick="openDeleteModal('deleteUserForm_<?php echo $user['id']; ?>')">Eliminar</button>
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

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este elemento? Esta acción borrará permanentemente los datos y no se puede deshacer.</p>
        </div>
        <div class="modal-footer form-actions">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <button type="button" class="btn-danger" id="confirmDeleteBtn">Sí, eliminar</button>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>