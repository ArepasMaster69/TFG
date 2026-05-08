<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard.php');
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ahora permitimos que tanto 'usuario' como 'admin' procesen reservas
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];
$eventId = (int) ($_POST['event_id'] ?? 0);
$seats = (int) ($_POST['seats'] ?? 1); // Capturamos las plazas solicitadas

try {
    $db = getDatabaseConnection();

    if ($action === 'reserve') {
        if ($seats <= 0) {
            header('Location: ../event.php?id=' . $eventId . '&error=' . urlencode('Debes reservar al menos 1 plaza.'));
            exit;
        }

        $eventStmt = $db->prepare('SELECT available_seats FROM events WHERE id = :id');
        $eventStmt->execute([':id' => $eventId]);
        $event = $eventStmt->fetch();

        if (!$event || $event['available_seats'] < $seats) {
            header('Location: ../event.php?id=' . $eventId . '&error=' . urlencode('No hay suficientes plazas disponibles.'));
            exit;
        }

        $checkStmt = $db->prepare('SELECT id FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
        $checkStmt->execute([':user_id' => $userId, ':event_id' => $eventId]);
        if ($checkStmt->fetch()) {
            header('Location: ../dashboard.php?error=' . urlencode('Ya tienes una reserva para este evento.'));
            exit;
        }

        $db->beginTransaction();
        $insertStmt = $db->prepare('INSERT INTO reservations (user_id, event_id, seats) VALUES (:user_id, :event_id, :seats)');
        $insertStmt->execute([':user_id' => $userId, ':event_id' => $eventId, ':seats' => $seats]);

        $updateStmt = $db->prepare('UPDATE events SET available_seats = available_seats - :seats WHERE id = :id');
        $updateStmt->execute([':seats' => $seats, ':id' => $eventId]);
        $db->commit();

        header('Location: ../dashboard.php?success=' . urlencode("Reserva de $seats plaza(s) realizada con éxito."));
        exit;
    }

    if ($action === 'cancel') {
        $reservationStmt = $db->prepare('SELECT id, seats FROM reservations WHERE user_id = :user_id AND event_id = :event_id');
        $reservationStmt->execute([':user_id' => $userId, ':event_id' => $eventId]);
        $reservation = $reservationStmt->fetch();

        if (!$reservation) {
            header('Location: ../dashboard.php?error=' . urlencode('No existe esa reserva.'));
            exit;
        }

        $seatsToRestore = (int) $reservation['seats'];

        $db->beginTransaction();
        $deleteStmt = $db->prepare('DELETE FROM reservations WHERE id = :id');
        $deleteStmt->execute([':id' => $reservation['id']]);

        $updateStmt = $db->prepare('UPDATE events SET available_seats = available_seats + :seats WHERE id = :id');
        $updateStmt->execute([':seats' => $seatsToRestore, ':id' => $eventId]);
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