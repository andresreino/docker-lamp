<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}
    // Sólo admin puede acceder a esta página. Si usuario intenta acceder escribiendo la url lo redirigimos a index
    if($_SESSION['usuario']['rol'] != 1){
        header("Location: ../index.php?redirigido=true");
    }		
?>
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../vista/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Buscador de tareas</h2>
                </div>
                <div class="container">
                    <form class="mb-5" action="tareas.php" method="GET" name="formulario" >
                        <div class="mb-3">
                            <label for="id">Usuario</label>
                            <select class="form-select" name="id" id="id" required>
                                <option value="" disabled selected>Seleccione un usuario</option>
                                <?php
                                include_once('../modelo/pdo.php');
                                include_once('../utils.php');
                                
                                // Función muestra usuarios (si hay) disponibles en el formulario
                                $resultado = listarUsuariosPDO();
                                
                                // OJO: No podemos usar función mostrarUsuariosForm($resultado) porque necesitamos
                                // que el option devuelva en el "value" el id del usuario, no el username
                                if ($resultado[0] && count($resultado[1]) > 0) {
                                    foreach ($resultado[1] as $usuario) {
                                        $id_usuario = $usuario->getId();
                                        $data = $usuario->getUsername();
                                        echo "<option value=$id_usuario>$data</option>";
                                    }   
                                } else {
                                    echo '<option value="" disabled >No existen usuarios</option>';
                                }
                                ?>
                            </select> 
                        </div>
                        <div class="mb-3">
                            <label for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado" >
                                <?php mostrarOpcionesEstadoTareaForm(); ?>
                            </select> 
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>  
                </div>
            </main>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); ?>
</body>
</html>