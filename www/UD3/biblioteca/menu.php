<!-- menu.php -->
<div class="sidebar" style="position: fixed; top: 100px; left: 0; height: calc(100vh - 100px); width: 200px; background-color: #28a745; padding-top: 20px;">
    <h3 class="text-center text-white">Menú</h3>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/index.php">Inicio</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/usuarios/usuarios.php">Usuarios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/usuarios/nuevoUsuarioForm.php">Registrar usuario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/libros/librosForm.php">Libros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/libros/nuevoLibroForm.php">Registrar libro</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white btn btn-outline-light mb-2" href="/UD3/biblioteca/libros/prestamosForm.php">Préstamos</a>
        </li>
    </ul>
</div>

<!-- Estilos en línea -->
<style>
/* Efecto hover para los enlaces */
.nav-link:hover {
    background-color: #ffffff;  /* Fondo blanco */
    color: #28a745 !important;  /* Texto verde oscuro cuando se pasa el cursor */
    border-radius: 4px;  /* Bordes redondeados */
}

/* Menú lateral con margen superior para evitar invadir el header */
.sidebar {
    position: fixed;
    top: 100px;  /* Ajuste para que quede debajo del header */
    left: 0;
    height: calc(100vh - 100px);  /* Altura completa menos la altura del header */
    width: 200px;
    background-color: #28a745;
    padding-top: 20px;
    overflow: hidden;  /* Elimina el scroll */
}
</style>
