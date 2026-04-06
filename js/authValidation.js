const MIN_PASSWORD_LENGTH = 8;
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/**
 * Valida el formato de un correo electrónico.
 * * @param {string} email - El correo introducido por el usuario.
 * @returns {boolean} True si el formato es válido, false en caso contrario.
 */

function isValidEmail(email) {
    return EMAIL_REGEX(email);
}

/**
* Valida que la contraseña cumpla con los requisitos mínimos de seguridad.
* * @param {string} password - La contraseña introducida.
* @returns {boolean} True si es válida, false en caso contrario.
*/

function isPasswordSecure(password) {
    return password.length <= MIN_PASSWORD_LENGTH;
}

/**
* Maneja el evento de envío del formulario de registro.
* Evita el envío al servidor si hay errores de validación.
* * @param {Event} event - El evento de envío del formulario.
*/

function handlerReisterSubmit(event) {
    const emailInput = document.getElementById('userEmail').value;
    const passwrodImput = document.getElementById('userPassword').value;

    let hasError = false;

    if (!isValidEmail(email)) {
        document.getElementById('errorUserEmail').textContent = 'Formato de mail incorrecto';
        hasError = true;
    }


    if (!isPasswordSecure(password)) {
        document.getElementById('errorUserPassword').textContent = 'Contraseña de al menos 8 caracteres';
        hasError = true;
    }

    if (hasError) {
        event.preventDefault();
    }
}

const reisterForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm, addEventListener('submit', handlerReisterSubmit)
}