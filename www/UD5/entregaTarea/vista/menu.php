<!-- Debido a Bootstrap no funciona correctemente tenemos que poner dinámicamente 'bg-light' si es claro o automático o nada si se elige dark-->
<nav class="col-md-3 col-lg-2 d-md-block <?php echo (isset($_COOKIE['tema']) && $_COOKIE['tema'] == 'dark') ? '' : 'bg-light' ;?> sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/index.php">Home</a>
            </li>
            <!-- Introducimos comprobación para que sólo muestre estos links si se es administrador -->
            <?php if($_SESSION['usuario']['rol'] == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/UD5/entregaTarea/init.php">Inicializar</a>
                </li>
            <?php } ?>
            
            <?php if($_SESSION['usuario']['rol'] == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/UD5/entregaTarea/usuarios/usuarios.php">Lista de usuarios</a>
                </li>
            <?php } ?>  
                
            <?php if($_SESSION['usuario']['rol'] == 1) { ?>
            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/usuarios/nuevoUsuarioForm.php">Nuevo usuario</a>
            </li>
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/tareas/tareas.php">Lista de tareas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/tareas/nuevaForm.php">Nueva tarea</a>
            </li>
            
            <?php if($_SESSION['usuario']['rol'] == 1) { ?>
            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/tareas/buscaTareas.php">Buscador de tareas</a>
            </li>
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link" href="/UD5/entregaTarea/controlador/logout.php" >Salir</a>
            </li>
        </ul>
<!-- Incluimos ruta absoluta en form para que se pueda acceder desde cualquier página cuando se pulse "Aplicar" en menú -->
        <form class="m-3 w-50" action="/UD5/entregaTarea/controlador/tema.php" method="POST">
            <select id="tema" name="tema" class="form-select mb-2" aria-label="Selector de tema">
                <option value="light" selected> Claro</option>
                <option value="dark">Oscuro</option>
                <option value="auto">Automático</option>
            </select>
            <button type="submit" class="btn btn-primary w-100">Aplicar</button>
        </form>
    </div>
</nav>  