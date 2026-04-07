<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: ../login.php?error=' . urlencode('Correo o contraseña incorrectos.'));
    exit;
}

try {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: ../login.php?error=' . urlencode('Correo o contraseña incorrectos.'));
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    header('Location: ../dashboard.php');
    exit;
} catch (PDOException $error) {
    header('Location: ../login.php?error=' . urlencode('Error al iniciar sesión. Intenta de nuevo más tarde.'));
    exit;
}
