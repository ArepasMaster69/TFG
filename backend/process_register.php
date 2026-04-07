<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['userName'] ?? '');
$email = trim($_POST['userEmail'] ?? '');
$password = $_POST['userPassword'] ?? '';

$errors = [];

if ($name === '') {
    $errors[] = 'El nombre completo es obligatorio.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El correo electrónico no tiene un formato válido.';
}

if (strlen($password) < 8) {
    $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
}

if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header('Location: ../register.php?' . $query);
    exit;
}

try {
    $db = getDatabaseConnection();

    $selectStmt = $db->prepare('SELECT id FROM users WHERE email = :email');
    $selectStmt->execute([':email' => $email]);

    if ($selectStmt->fetch()) {
        header('Location: ../register.php?error=' . urlencode('Ya existe una cuenta con ese correo electrónico.'));
        exit;
    }

    // Verificar si hay admins
    $adminStmt = $db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
    $adminStmt->execute([':role' => 'admin']);
    $adminCount = $adminStmt->fetchColumn();

    $role = $adminCount == 0 ? 'admin' : 'usuario';

    $insertStmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
    $insertStmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':role' => $role,
    ]);

    header('Location: ../login.php?success=' . urlencode('Registro completado. Ya puedes iniciar sesión.'));
    exit;
} catch (PDOException $error) {
    header('Location: ../register.php?error=' . urlencode('Error al procesar el registro. Intenta de nuevo más tarde.'));
    exit;
}
