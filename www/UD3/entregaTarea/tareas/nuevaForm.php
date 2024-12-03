<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nueva tarea</h2>
                </div>
                <div class="container">
                    <form class="mb-5" action="nueva.php" method="POST" name="formulario" >
                        <div class="mb-3">
                            <label class="form-label" >Título</label>
                            <input class="form-control" type="text" name="titulo" id="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" >Descripción</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado" required>
                                <!-- disabled: opción no válida al enviar form ## selected: opción visible al cargar -->
                                <option value="" disabled selected>Seleccione un estado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="enproceso">En proceso</option>
                                <option value="completada">Completada</option>
                            </select> 
                        </div>
                        <div class="mb-3">
                            <label for="username">Usuario</label>
                            <select class="form-select" name="username" id="username" required>
                                <option value="" disabled selected>Seleccione un usuario</option>
                                <?php
                                include_once('../database.php');
                                $resultado = listarUsuariosMysqli();
                                // Función muestra usuarios (si hay) disponibles en el formulario
                                mostrarUsuariosForm($resultado);
                                ?>
                            </select> 
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>  
                </div>
            </main>
        </div>
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>