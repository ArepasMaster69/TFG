<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard.php');
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'usuario') !== 'usuario') {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];
$eventId = (int) ($_POST['event_id'] ?? 0);

try {
    $db = getDatabaseConnection();

    if ($action === 'reserve') {
        $eventStmt = $db->prepare('SELECT available_seats FROM events WHERE id = :id');
        $eventStmt->execute([':id' => $eventId]);
        $event = $eventStmt->fetch();

        if (!$event || $event['available_seats'] <= 0) {
            header('Location: ../event.php?id=' . $eventId . '&error=' . urlencode('No hay plazas disponibles.'));
            exit;
        }

        $checkStmt = $db->prepare('SELECT id FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
        $checkStmt->execute([':user_id' => $userId, ':event_id' => $eventId]);
        if ($checkStmt->fetch()) {
            header('Location: ../dashboard.php?error=' . urlencode('Ya has reservado este evento.'));
            exit;
        }

        $db->beginTransaction();
        $insertStmt = $db->prepare('INSERT INTO reservations (user_id, event_id) VALUES (:user_id, :event_id)');
        $insertStmt->execute([':user_id' => $userId, ':event_id' => $eventId]);

        $updateStmt = $db->prepare('UPDATE events SET available_seats = available_seats - 1 WHERE id = :id');
        $updateStmt->execute([':id' => $eventId]);
        $db->commit();

        header('Location: ../dashboard.php?success=' . urlencode('Reserva realizada con éxito.'));
        exit;
    }

    if ($action === 'cancel') {
        $reservationStmt = $db->prepare('SELECT id FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
        $reservationStmt->execute([':user_id' => $userId, ':event_id' => $eventId]);
        $reservation = $reservationStmt->fetch();

        if (!$reservation) {
            header('Location: ../dashboard.php?error=' . urlencode('No existe esa reserva.'));
            exit;
        }

        $db->beginTransaction();
        $deleteStmt = $db->prepare('DELETE FROM reservations WHERE id = :id');
        $deleteStmt->execute([':id' => $reservation['id']]);

        $updateStmt = $db->prepare('UPDATE events SET available_seats = available_seats + 1 WHERE id = :id');
        $updateStmt->execute([':id' => $eventId]);
        $db->commit();

        header('Location: ../dashboard.php?success=' . urlencode('Reserva cancelada correctamente.'));
        exit;
    }

    header('Location: ../dashboard.php?error=' . urlencode('Acción de reserva no válida.'));
    exit;
} catch (PDOException $error) {
    if ($db && $db->inTransaction()) {
        $db->rollBack();
    }
    header('Location: ../dashboard.php?error=' . urlencode('Error al procesar la reserva.'));
    exit;
}
