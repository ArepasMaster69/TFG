<?php 
$pageTitle = 'Registro de Usuario - Eventos Locales';
require_once 'includes/header.php';
?>

<main class="auth-container">
    <section class="auth-card">
        <h2>Crear Cuenta</h2>
        <p class="auth-subtitle">Únete a nuestra comunidad para reservar eventos.</p>
        
        <form id="registerForm" action="backend/process_register.php" method="POST" class="form-layout">
            <div class="form-group">
                <label for="userName">Nombre Completo</label>
                <input type="text" id="userName" name="userName" required>
            </div>

            <div class="form-group">
                <label for="userEmail">Correo Electrónico</label>
                <input type="email" id="userEmail" name="userEmail"required>
            </div>

            <div class="form-group">
                <label for="userPassword">Contraseña</label>
                <input type="password" id="userPassword" name="userPassword" required>
            </div>

            <button type="submit" class="btn-primary" id="btnRegister">Registrarse ahora</button>
        </form>
        
        <p style="text-align: center; margin-top: var(--spacingMd); font-size: 0.9rem; color: var(--colorTextLight);">
            ¿Ya eres miembro? <a href="login.php" style="color: var(--colorBrand); font-weight: 600; text-decoration: none;">Inicia sesión</a>
        </p>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>