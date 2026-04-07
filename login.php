<?php
$pageTitle = 'Iniciar Sesión - Eventos Locales';
require_once 'includes/header.php'; 
?>

<main class="auth-container">
    <section class="auth-card">
        <h2 style="text-align: center; margin-bottom: var(--spacingSm);">Iniciar Sesión</h2>
        <p style="text-align: center; color: var(--colorTextLight); margin-bottom: var(--spacingLg);">Accede a tu panel de control.</p>
        
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form id="loginForm" action="backend/process_login.php" method="POST" class="form-layout">
            <div class="form-group">
                <label for="loginEmail">Correo Electrónico</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>

            <div class="form-group">
                <label for="loginPassword">Contraseña</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Acceder</button>
        </form>
        
        <p style="text-align: center; margin-top: var(--spacingMd); font-size: 0.9rem;">
            ¿No tienes cuenta? <a href="register.php" style="color: var(--colorPrimary);">Regístrate</a>
        </p>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>