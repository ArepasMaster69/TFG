
document.addEventListener('DOMContentLoaded', () => {

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

    const navbar = document.querySelector('.main-nav');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.08)';
            } else {
                navbar.style.boxShadow = 'none';
            }
        });
    }


    const urlParams = new URLSearchParams(window.location.search);
    const errorMsg = urlParams.get('error');
    const successMsg = urlParams.get('success');

    if (errorMsg || successMsg) {
        const type = errorMsg ? 'error' : 'success';
        const message = errorMsg || successMsg;

        showToast(message, type);

        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);

        const oldAlerts = document.querySelectorAll('.alert');
        oldAlerts.forEach(alert => alert.style.display = 'none');
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icon = type === 'error' ? '⚠️' : '✅';

        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">${message}</div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }
});