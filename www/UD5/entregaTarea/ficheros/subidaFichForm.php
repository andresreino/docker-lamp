<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
        exit();
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
                    <h2>Adjuntar archivo</h2>
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
                    include_once('../modelo/mysqli.php');

                    if(!empty($_GET)){
                        $idTarea = $_GET["id"];         
                    }                        
                ?>
                    <form class="mb-5" action="../ficheros/subidaFichProc.php?id=<?php echo $idTarea; ?>" method="POST" name="formulario" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label" >Nombre</label>
                            <input class="form-control" type="text" name="nombreFichero" id="nombreFichero" required>
                        </div>
                        <?php
                            // Muestra mensaje error si no valida debajo de este campo (viene en un array) 
                            if(!empty($_SESSION["usuario"]["messages"]) && isset($_SESSION["usuario"]["messages"]["nombre"])){
                                mostrarMensajeSESSIONVariableUnica($_SESSION["usuario"]["messages"]["nombre"]);
                            }
                        ?>
                        <div class="mb-3">
                            <label class="form-label" >Descripción</label>
                            <textarea class="form-control" type="text" name="descripcion" id="descripcion" required></textarea>
                        </div>
                        <?php 
                            if(!empty($_SESSION["usuario"]["messages"]) && isset($_SESSION["usuario"]["messages"]["descripcion"])){
                                mostrarMensajeSESSIONVariableUnica($_SESSION["usuario"]["messages"]["descripcion"]);
                            }
                        ?>
                        <div class="mb-3">                    
                            <label class="form-label" >Seleccionar archivo</label><br>
                            <p></p></P><input type="file" name="fichero" required></p>
                        <?php 
                             if(!empty($_SESSION["usuario"]["messages"]) && isset($_SESSION["usuario"]["messages"]["formato"])){
                                mostrarMensajeSESSIONVariableUnica($_SESSION["usuario"]["messages"]["formato"]);
                            }
                        ?>
                            <button type="submit" class="btn btn-primary">Subir archivo</button>
                        </div>
                    </form>   
                </div>
            </main>
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

