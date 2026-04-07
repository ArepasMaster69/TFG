<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin.php');
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
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$venue = trim($_POST['venue'] ?? '');
$eventDate = trim($_POST['event_date'] ?? '');
$totalSeats = (int) ($_POST['total_seats'] ?? 0);

$errors = [];

if ($title === '') {
    $errors[] = 'El título es obligatorio.';
}
if ($description === '') {
    $errors[] = 'La descripción es obligatoria.';
}
if ($venue === '') {
    $errors[] = 'El lugar es obligatorio.';
}
if ($eventDate === '') {
    $errors[] = 'La fecha y hora son obligatorias.';
}
if ($totalSeats <= 0) {
    $errors[] = 'Las plazas totales deben ser un número positivo.';
}

if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header('Location: ../admin_event_form.php?action=' . urlencode($action) . '&' . $query . (isset($_POST['event_id']) ? '&id=' . (int) $_POST['event_id'] : ''));
    exit;
}

try {
    $db = getDatabaseConnection();

    if ($action === 'create') {
        $insertStmt = $db->prepare('INSERT INTO events (title, description, venue, event_date, total_seats, available_seats) VALUES (:title, :description, :venue, :event_date, :total_seats, :available_seats)');
        $insertStmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':venue' => $venue,
            ':event_date' => $eventDate,
            ':total_seats' => $totalSeats,
            ':available_seats' => $totalSeats,
        ]);

        header('Location: ../admin.php?success=' . urlencode('Evento creado correctamente.'));
        exit;
    }

    if ($action === 'edit' && !empty($_POST['event_id'])) {
        $eventId = (int) $_POST['event_id'];
        $stmt = $db->prepare('SELECT total_seats, available_seats FROM events WHERE id = :id');
        $stmt->execute([':id' => $eventId]);
        $existingEvent = $stmt->fetch();

        if (!$existingEvent) {
            header('Location: ../admin.php?error=' . urlencode('Evento no encontrado.'));
            exit;
        }

        $reservedCount = $existingEvent['total_seats'] - $existingEvent['available_seats'];
        if ($totalSeats < $reservedCount) {
            header('Location: ../admin_event_form.php?action=edit&id=' . $eventId . '&error=' . urlencode('No se puede reducir las plazas totales por debajo de las reservas existentes.'));
            exit;
        }

        $newAvailable = $totalSeats - $reservedCount;
        $updateStmt = $db->prepare('UPDATE events SET title = :title, description = :description, venue = :venue, event_date = :event_date, total_seats = :total_seats, available_seats = :available_seats WHERE id = :id');
        $updateStmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':venue' => $venue,
            ':event_date' => $eventDate,
            ':total_seats' => $totalSeats,
            ':available_seats' => $newAvailable,
            ':id' => $eventId,
        ]);

        header('Location: ../admin.php?success=' . urlencode('Evento actualizado correctamente.'));
        exit;
    }

    if ($action === 'delete' && !empty($_POST['event_id'])) {
        $deleteStmt = $db->prepare('DELETE FROM events WHERE id = :id');
        $deleteStmt->execute([':id' => (int) $_POST['event_id']]);

        header('Location: ../admin.php?success=' . urlencode('Evento eliminado correctamente.'));
        exit;
    }

    header('Location: ../admin.php?error=' . urlencode('Acción no válida.'));
    exit;
} catch (PDOException $error) {
    header('Location: ../admin.php?error=' . urlencode('Error al procesar el evento.'));
    exit;
}
