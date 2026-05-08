/**
 * main.js - Motor dinámico de Civitas (Buscador, Toasts y Modales)
 */

let currentFormToSubmit = null;

/**
 * Abre el modal de confirmación de eliminación.
 * @param {string} formId - El ID del formulario que se enviará si se confirma.
 */
window.openDeleteModal = function (formId) {
    currentFormToSubmit = document.getElementById(formId);
    const modalElement = document.getElementById('deleteModal');
    if (modalElement) {
        modalElement.classList.add('isActive');
    }
};

/**
 * Cierra el modal de confirmación sin ejecutar ninguna acción.
 */
window.closeDeleteModal = function () {
    currentFormToSubmit = null;
    const modalElement = document.getElementById('deleteModal');
    if (modalElement) {
        modalElement.classList.remove('isActive');
    }
};

/**
 * Ejecuta el envío del formulario almacenado temporalmente.
 */
function executeDeletion() {
    if (currentFormToSubmit) {
        currentFormToSubmit.submit();
    }
}

/**
 * Muestra una notificación flotante (Toast).
 * @param {string} message - El mensaje a mostrar.
 * @param {string} type - Tipo de notificación ('success' o 'error').
 */
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon = type === 'error' ? '⚠️' : '✅';
    toast.innerHTML = `<div class="toast-icon">${icon}</div><div class="toast-content">${message}</div>`;

    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// Configuración de los eventos
document.addEventListener('DOMContentLoaded', () => {

    // --- 1. BUSCADOR DE EVENTOS ---
    const searchInput = document.getElementById('eventSearch');
    const eventCards = document.querySelectorAll('.event-card');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            eventCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('.event-desc') ? card.querySelector('.event-desc').textContent.toLowerCase() : '';

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'flex';
                    card.style.animation = 'fadeIn 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // --- 2. GESTIÓN DE TOASTS DESDE PHP ---
    const urlParams = new URLSearchParams(window.location.search);
    const errorMsg = urlParams.get('error');
    const successMsg = urlParams.get('success');

    if (errorMsg || successMsg) {
        const type = errorMsg ? 'error' : 'success';
        const message = errorMsg || successMsg;

        showToast(message, type);

        // Limpiamos la URL sin recargar la página
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);

        // Ocultamos las alertas viejas (por si acaso queda alguna en el HTML)
        const oldAlerts = document.querySelectorAll('.alert');
        oldAlerts.forEach(alert => alert.style.display = 'none');
    }

    // --- 3. CONFIRMAR ELIMINACIÓN ---
    const confirmDeleteButton = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', executeDeletion);
    }
});