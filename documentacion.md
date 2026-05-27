# Documentación del Proyecto: PortalEventos (TFG)
**Autores:** Samuel Parra y Jorge Garre


## 1. Introducción
**PortalEventos** es una aplicación web pensada para que los ayuntamientos o centros culturales puedan gestionar y publicar sus actividades, y para que los ciudadanos puedan descubrirlas y reservar su plaza fácilmente. 

Hemos intentado hacer un proyecto que no solo funcione por detrás, sino que se vea como una aplicación real y moderna que la gente querría usar.

## 2. Tecnologías que hemos utilizado
Para desarrollar este proyecto hemos utilizado las herramientas y lenguajes que hemos aprendido en el ciclo:
* **Frontend:** HTML5, CSS3 (usando variables y Flexbox/Grid para que sea *responsive*) y JavaScript puro (Vanilla JS) para darle dinamismo a la web sin tener que recargar la página todo el rato.
* **Backend:** PHP nativo.
* **Base de datos:** MySQL. Para conectarnos desde PHP hemos usado **PDO**, ya que nos permite hacer consultas preparadas y así evitamos ataques de inyección SQL.
* **Librerías externas:** Solo hemos incluido **Quill.js**, que es un editor de texto enriquecido muy ligero para que el administrador pueda poner negritas o listas al crear los eventos.

## 3. Estructura de la Base de Datos
Toda la configuración está en el archivo `config/database.php`. Hemos diseñado la base de datos con tres tablas principales que se relacionan entre sí:

1. **users:** Guarda los datos de la gente que se registra. Tiene un campo `role` que diferencia si eres un `'usuario'` normal o un `'admin'`. (Dato importante: la contraseña se guarda encriptada usando la función `password_hash()` de PHP).
2. **events:** Guarda la información del evento (título, lugar, fecha). Tiene dos columnas para las plazas: `total_seats` (las plazas iniciales) y `available_seats` (las que van quedando libres).
3. **reservations:** Es la tabla intermedia que une a los usuarios con los eventos. Guarda el `user_id` y el `event_id`. Si se borra un evento o un usuario, sus reservas se borran automáticamente en cascada.

## 4. Estructura del Proyecto (Carpetas y Archivos)
Para no tener todo el código mezclado y hacer un "código espagueti", hemos separado la parte visual (las vistas) de la parte que procesa los datos (la lógica).

###  Vistas (Archivos en la raíz)
Son los archivos que ve el usuario. Todos incluyen un `header.php` y un `footer.php` para no repetir el código del menú.
* `index.php`: Es el "escaparate". Muestra los eventos futuros. Cualquiera puede verlos, pero para reservar te pide iniciar sesión.
* `login.php` y `register.php`: Formularios de acceso.
* `dashboard.php`: Es el panel del usuario normal. Aquí ve sus reservas activas (en verde) y debajo sugerencias de otros eventos a los que se puede apuntar.
* `admin.php`: El panel del administrador. Muestra una tabla con todos los eventos para editarlos o borrarlos.
* `admin_event_form.php`: El formulario donde el administrador crea o edita un evento. Aquí es donde cargamos la librería Quill.js.
* `event.php`: Una página dedicada a ver los detalles de un evento en concreto en grande.

###  Backend (Procesamiento de formularios)
En la carpeta `/backend` están los archivos PHP que no tienen HTML. Solo reciben datos por `POST`, hablan con la base de datos y luego redirigen al usuario a la página correspondiente usando `header('Location: ...')`. 
Por ejemplo: `process_login.php`, `process_register.php`, `process_reservation.php` o `process_event.php`.

###  CSS y JS
* **`css/styles.css`:** Aquí está todo el diseño. Nos hemos decidido por un **Modo Oscuro Nativo** porque le da un aspecto mucho más profesional y moderno a la aplicación. Usamos tarjetas con sombras suaves, bordes redondeados y una cuadrícula estricta para que nada se deforme.
* **`js/main.js`:** Es el "motor" visual del proyecto. Lo hemos programado para hacer tres cosas clave:
  1. **Buscador en tiempo real:** Filtra las tarjetas de eventos mientras escribes sin tener que ir al servidor.
  2. **Notificaciones (Toasts):** En lugar de sacar cajas de error de PHP que rompen el diseño, el JS lee la URL y muestra un mensajito flotante que desaparece a los 4 segundos.
  3. **Modales (Popups):** Cuando el administrador le da a "Eliminar evento", en lugar de sacar la alerta fea del navegador (`confirm()`), abrimos una ventana emergente hecha a medida.

## 5. Decisiones de diseño y usabilidad (UX/UI)
Durante el desarrollo nos dimos cuenta de algunas cosas que mejoraban la experiencia del usuario y las aplicamos:
* **Estados vacíos (Empty States):** Si un usuario entra y no hay eventos, no queríamos que viera un hueco en blanco que parece un error. Hemos diseñado unas cajas amigables con un icono de calendario que avisan de que por ahora no hay nada.
* **Control de errores de sesión:** Si intentas ir por la URL a `admin.php` siendo un usuario normal, el sistema te detecta y te expulsa al login para mantener la seguridad.
* **Protección contra borrado accidental:** En el panel de control de usuarios (`admin_users.php`), el sistema oculta el botón de eliminar de tu propia cuenta para que el administrador no se pueda borrar a sí mismo por error y dejar el sistema sin control.

## 6. Funciones auxiliares y comportamiento del código (explicaciones detalladas)
Aquí explicamos en lenguaje natural las funciones clave que aparecen en el código y su propósito, para que quien lea el proyecto entienda el flujo sin tener que inspeccionar siempre el código fuente.

### 6.1 `config/database.php`
- `getDatabaseConnection()` : Establece y devuelve una conexión PDO a la base de datos usando las constantes de configuración (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`). Configura opciones seguras de PDO (modo de error por excepciones, fetch asociativo por defecto y sin emulación de prepared statements). Al obtener la conexión, llama a `initializeDatabaseSchema()` para asegurarse de que las tablas necesarias existen. Si la conexión falla, detiene la ejecución con un mensaje claro.
- `initializeDatabaseSchema(PDO $db)` : Crea las tablas principales si no existen: `users`, `events` y `reservations`. Describe las columnas más importantes y las relaciones:
  - `users`: `id`, `name`, `email` (único), `password` (almacenada con `password_hash()`), `role` (`usuario` o `admin`), `created_at`.
  - `events`: `id`, `title`, `description`, `venue`, `event_date`, `total_seats`, `available_seats`, `created_at`.
  - `reservations`: `id`, `user_id`, `event_id`, `seats`, `reserved_at`. Tiene una restricción única por `(user_id, event_id)` para evitar duplicados y claves foráneas que eliminan en cascada cuando se borra un usuario o un evento.

### 6.2 `js/authValidation.js` (validación del lado cliente)
- `isValidEmail(email)` : Comprueba el formato del correo usando una expresión regular. Devuelve `true` si cumple el patrón básico de correo y `false` en caso contrario.
- `isPasswordSecure(password)` : Valida que la contraseña tenga al menos `8` caracteres (`MIN_PASSWORD_LENGTH`). Si se quiere endurecer la política (mayúsculas, números, símbolos), aquí es el lugar indicado.
- `handleRegisterSubmit(event)` : Handler que se asocia al formulario de registro (`registerForm`). Al enviar el formulario valida `email` y `password`, muestra mensajes de error en elementos con `id` `errorUserEmail` y `errorUserPassword` y evita el envío (`event.preventDefault()`) si hay errores. Es la primera línea de defensa antes de la validación en el servidor.

### 6.3 `js/main.js` (toasts, modales y buscador)
- `openDeleteModal(formId)` : Abre un modal de confirmación para eliminación. Almacena la referencia del formulario cuyo `id` se pasa como `formId` (se guardará en `currentFormToSubmit`) y añade la clase `isActive` al elemento `#deleteModal` para mostrarlo.
- `closeDeleteModal()` : Cierra el modal eliminando la clase `isActive` y borra la referencia al formulario almacenado en `currentFormToSubmit`.
- `executeDeletion()` : Si hay un formulario almacenado en `currentFormToSubmit`, lo envía con `.submit()`. Este método se llama desde el botón de confirmación dentro del modal (`confirmDeleteBtn`).
- `showToast(message, type)` : Crea y muestra una notificación flotante (toast). `type` suele ser `'success'` o `'error'` y determina el icono y la clase CSS. Inserta el elemento en el `body`, aplica la animación de aparición y lo oculta automáticamente tras 4 segundos, eliminando finalmente el elemento del DOM.

También en `main.js` se inicializan comportamientos al cargar la página (`DOMContentLoaded`):
- El buscador en tiempo real (input con id `eventSearch`) filtra elementos con la clase `.event-card` comparando título y descripción.
- La lectura de parámetros `error` o `success` en la URL para mostrar `toasts` cuando una acción del servidor redirige con mensajes (por ejemplo `?success=Reserva realizada`). Tras mostrar el toast, se limpia la URL con `history.replaceState()` para no volver a mostrar el mensaje al recargar.
- El botón de confirmación de eliminación con id `confirmDeleteBtn` se enlaza para ejecutar `executeDeletion()`.

---

