<?php 
$pageTitle = 'Registro de Usuario - Eventos Locales';
require_once 'includes/header.php'; 
?>

<section class="auth-card">
    <h2>Registro de Usuario</h2>
    <p>Crea tu cuenta para gestionar y reservar eventos locales.</p>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    
    <form id="registerForm" action="backend/process_register.php" method="POST" class="form-layout">
        <div class="form-group">
            <label for="userName">Nombre Completo</label>
            <input type="text" id="userName" name="userName" required>
            <span class="error-message" id="errorUserName"></span>
        </div>

        <div class="form-group">
            <label for="userEmail">Correo Electrónico</label>
            <input type="email" id="userEmail" name="userEmail" required>
            <span class="error-message" id="errorUserEmail"></span>
        </div>

        <div class="form-group">
            <label for="userPassword">Contraseña</label>
            <input type="password" id="userPassword" name="userPassword" required>
            <span class="error-message" id="errorUserPassword"></span>
        </div>

        <button type="submit" class="btn-primary" id="btnRegister">Crear Cuenta</button>
    </form>
    <p class="auth-link">¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
</section>

<?php require_once 'includes/footer.php'; ?>