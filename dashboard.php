<?php
$pageTitle = 'Mis Reservas y Eventos';
require_once 'includes/header.php'; 
?>

<div class="container" style="margin-top: var(--spacingLg);">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacingLg);">
        <h2>Próximos Eventos</h2>
        <p>Bienvenido, <strong>Usuario</strong></p> 
    </header>

    <section class="events-grid">
        <article class="event-card">
            <h3>Taller de Fotografía</h3>
            <p class="event-meta"> 15 de Noviembre | 📍 Centro Cívico</p>
            <p style="font-size: 0.9rem; margin-bottom: var(--spacingMd);">Aprende los conceptos básicos de la fotografía digital.</p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--colorTextLight);">Plazas: 5/20</span>
                <button class="btn-primary">Reservar Plaza</button>
            </div>
        </article>
        </section>
</div>

<?php require_once 'includes/footer.php'; ?>