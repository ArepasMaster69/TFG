<?php
$pageTitle = 'Iniciar Sesión - Eventos Locales';
require_once 'includes/header.php';
?>

<main class="auth-container">
    <section class="auth-card">
        <h2>Iniciar Sesión</h2>
        <p class="auth-subtitle">Accede a tu panel de control para gestionar tus reservas.</p>
        
        <form id="loginForm" action="backend/process_login.php" method="POST" class="form-layout">
            <div class="form-group">
                <label for="loginEmail">Correo Electrónico</label>
                <input type="email" id="loginEmail" name="email"  required>
            </div>

            <div class="form-group">
                <label for="loginPassword">Contraseña</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Acceder al sistema</button>
        </form>
        
        <p style="text-align: center; margin-top: var(--spacingMd); font-size: 0.9rem; color: var(--colorTextLight);">
            ¿Aún no tienes cuenta? <a href="register.php" style="color: var(--colorBrand); font-weight: 600; text-decoration: none;">Regístrate aquí</a>
        </p>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>