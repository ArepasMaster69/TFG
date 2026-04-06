<?php
/**
 * Main entry point for the Event Management Platform.
 * Displays the public landing page and upcoming events.
 */

$pageTitle = 'Inicio - Eventos Locales';
require_once 'includes/header.php';
?>

<header class="page-header" style="text-align: center; margin-bottom: var(--spacingLg);">
    <h1>Descubre actividades en tu municipio</h1>
    <p style="color: var(--colorTextLight);">Reserva tu plaza de forma rápida y sencilla.</p>
</header>

<section class="events-grid">
    <article class="event-card">
        <h3>Concierto de Jazz</h3>
        <p class="event-meta">Centro Cultural - 20 de Octubre</p>
        <button class="btn-primary">Ver detalles</button>
    </article>

    <article class="event-card">
        <h3>Taller de Cerámica</h3>
        <p class="event-meta">Plaza Mayor - 22 de Octubre</p>
        <button class="btn-primary">Ver detalles</button>
    </article>

    <article class="event-card">
        <h3>Torneo de Ajedrez</h3>
        <p class="event-meta">Biblioteca Municipal - 25 de Octubre</p>
        <button class="btn-primary">Ver detalles</button>
    </article>
</section>

<?php require_once 'includes/footer.php'; ?>