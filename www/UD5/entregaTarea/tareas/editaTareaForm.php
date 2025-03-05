<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}
?>
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../vista/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>
            <?php include_once('../utils.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Editar tarea</h2>
                </div>
                <?php
                    include_once('../utils.php');
                    // Muestra en parte superior mensaje de error simple o éxito en la acción
                    if(isset($_SESSION["usuario"]["messages"]) && !is_array($_SESSION["usuario"]["messages"])){
                        mostrarMensajeSESSION();
                    }
                ?>  
                <div class="container">
                    <?php
                    include_once('../utils.php');
                    include_once('Tarea.php');
                    include('../modelo/mysqli.php');
                    include_once('../modelo/pdo.php');

                    if(!empty($_GET)){
                        $id = $_GET["id"];
                        // Buscamos en BD la tarea y devuelve T/F (índice 0) y un objeto Tarea o mensaje error (índice 1)
                        $tarea = buscarTarea($id);
                
                        $titulo = $tarea[1]->getTitulo();    
                        $descripcion = $tarea[1]->getDescripcion();    
                        $estado = $tarea[1]->getEstado();      
                        $idUsuario = $tarea[1]->getIdUsuario(); 

                        // Buscamos en BD el usuario de esta tarea y devuelve objeto Usuario
                        $usuario = buscarUsuario($idUsuario);      
                        $username = $usuario[1]->getUsername();    
                    }                      
                    ?>
                    <form class="mb-5" action="editaTarea.php" method="POST" name="formulario" >
                        <div class="mb-3">
                            <label class="form-label" >Título</label>
                            <input class="form-control" type="text" name="titulo" id="titulo" value="<?php echo $titulo ?>" required>
                        </div>
                        <?php 
                            // Muestra mensaje error si no valida debajo de este campo (viene en un array)
                            if(!empty($_SESSION["usuario"]["messages"]) && isset($_SESSION["usuario"]["messages"]["titulo"])){
                                mostrarMensajeSESSIONVariableUnica($_SESSION["usuario"]["messages"]["titulo"]);
                            }
                        ?>
                        <div class="mb-3">
                            <label class="form-label" >Descripción</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion ?>" required>
                        </div>
                        <?php 
                            // Muestra mensaje error si no valida debajo de este campo (viene en un array)
                            if(!empty($_SESSION["usuario"]["messages"]) && isset($_SESSION["usuario"]["messages"]["descripcion"])){
                                mostrarMensajeSESSIONVariableUnica($_SESSION["usuario"]["messages"]["descripcion"]);
                            }
                        ?>
                        <div class="mb-3">
                            <label for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado" required>
                                <!-- disabled: opción no válida al enviar form / selected: opción visible al cargar -->
                                <option value="" disabled >Seleccione un estado</option>
                                <!-- Para que muestre estado seleccionado al cargar formulario -->
                                <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="enproceso" <?php echo $estado === 'enproceso' ? 'selected' : ''; ?>>En proceso</option>
                                <option value="completada" <?php echo $estado === 'completada' ? 'selected' : ''; ?>>Completada</option>
                            </select> 
                        </div>
                        <!-- Si usuario tiene rol 1 (admin) se muestra esta parte del form, si es 0 (usuario) no -->
                        <?php if($_SESSION['usuario']['rol'] == 1) { ?>
                        <div class="mb-3">
                            <label for="username">Usuario</label>
                            <select class="form-select" name="username" id="username" required>
                                <option value="" disabled >Seleccione un usuario</option>
                                <?php
                                $resultado = listarUsuariosMysqli();
                                // Función muestra usuarios (si hay) disponibles en el formulario
                                mostrarUsuarioSeleccionadoForm($resultado, $username);
                                ?>
                            </select> 
                        </div>
            <!-- Si usuario tiene rol 0 (usuario) no se muestran otros usuarios y se envío oculto username, que cogemos de $_SESSION -->
                        <?php } else { ?>
                            <input type="hidden" name="username" id="username" value="<?php echo htmlspecialchars($_SESSION["usuario"]["username"]); ?>">         
                        <?php } ?>
                        <!-- input oculto que recoge el id de la tarea para enviar por POST-->
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>                          
                </div>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); 
    if(isset($_SESSION["usuario"]["success"]) && isset($_SESSION["usuario"]["messages"])){
        unset($_SESSION["usuario"]["success"]);
        unset($_SESSION["usuario"]["messages"]);
    } 
    ?>
</body>
</html>