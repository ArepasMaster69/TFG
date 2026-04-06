<?php
$pageTitle = 'Panel de Administración';
require_once 'includes/header.php'; 
?>

<div class="container" style="margin-top: var(--spacingLg);">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacingLg);">
        <h2>Gestión de Eventos</h2>
        <button class="btn-primary" style="background-color: #10b981;">+ Nuevo Evento</button>
    </header>

    <div style="background-color: var(--colorCard); border: 1px solid var(--colorBorder); border-radius: var(--borderRadius);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--spacingMd); border-bottom: 1px solid var(--colorBorder);">
            <div>
                <h4 style="margin-bottom: 0.2rem;">Taller de Fotografía</h4>
                <p class="event-meta" style="margin-bottom: 0;">15 Nov | 20 Plazas totales</p>
            </div>
            <div style="display: flex; gap: var(--spacingSm);">
                <button class="btn-primary" style="background-color: #3b82f6; font-size: 0.8rem;">Editar</button>
                <button class="btn-primary" style="background-color: #ef4444; font-size: 0.8rem;">Eliminar</button>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>