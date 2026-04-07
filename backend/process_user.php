<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin_users.php');
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'usuario') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? '';
$userId = (int) ($_POST['user_id'] ?? 0);

if ($userId <= 0) {
    header('Location: ../admin_users.php?error=' . urlencode('ID de usuario inválido.'));
    exit;
}

try {
    $db = getDatabaseConnection();

    if ($action === 'change_role') {
        $newRole = $_POST['new_role'] ?? '';
        if (!in_array($newRole, ['usuario', 'admin'])) {
            header('Location: ../admin_users.php?error=' . urlencode('Rol inválido.'));
            exit;
        }

        $stmt = $db->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->execute([$newRole, $userId]);

        header('Location: ../admin_users.php?success=' . urlencode('Rol actualizado correctamente.'));
        exit;

    } elseif ($action === 'delete') {
        // No permitir borrar a sí mismo
        if ($userId === $_SESSION['user_id']) {
            header('Location: ../admin_users.php?error=' . urlencode('No puedes eliminar tu propia cuenta.'));
            exit;
        }

        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userId]);

        header('Location: ../admin_users.php?success=' . urlencode('Usuario eliminado correctamente.'));
        exit;

    } else {
        header('Location: ../admin_users.php?error=' . urlencode('Acción inválida.'));
        exit;
    }

} catch (PDOException $error) {
    header('Location: ../admin_users.php?error=' . urlencode('Error al procesar la solicitud.'));
    exit;
}
?>