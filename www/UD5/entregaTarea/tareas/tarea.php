<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}		
?>
<!DOCTYPE html>
<!-- Dinámicamente se imprime el valor de la cookie (si existe). Si no coge light por defecto -->
<html lang="es" data-bs-theme="<?php echo isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'light';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD5. Tarea DWCS</title>
    <link rel="stylesheet" href="tarea.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once('../vista/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Tarea</h2>
                </div>
                    <?php
                    include_once('../utils.php');
                    if(!empty($_SESSION["usuario"]["messages"])){
                        mostrarMensajeSESSION();
                    }
                    ?>
                
                    <?php
                    include_once('../utils.php');
                    include_once('../modelo/mysqli.php');
                    include_once('../modelo/pdo.php');
                    include_once('Tarea.php');
                    include_once('../interfaces/FicherosDBImp.php');


                    if(!empty($_GET)){
                        $id_tarea = $_GET["id"];
                        $tarea = buscarTarea($id_tarea);
                        
                        if($tarea){
                            $titulo = $tarea[1]->getTitulo();    
                            $descripcion = $tarea[1]->getDescripcion();    
                            $estado = $tarea[1]->getEstado();
                            $idUsuario = $tarea[1]->getIdUsuario();     

                            // Buscamos el objeto Usuario por su id para obtener su username
                            $usuario = buscarUsuario($idUsuario);
                            $username = $usuario[1]->getUsername();                
                            
                            // Usamos un objeto de la clase FicherosDBImp, que implementa la interfaz FicherosDBInt
                            $objetoFicheroDBImp = new FicherosDBImp();

                            try {
                                // Si correcto devuelve array con objetos Fichero, si algo falla devuelve excepción
                                $listaFicheros = $objetoFicheroDBImp->listaFicheros($id_tarea);
                                $ficherosTarea = [true, $listaFicheros];
                            } catch (Exception $e) {
                                $error = $e->getMessage();
                                $ficherosTarea = [false, $error];
                            }

                            if($ficherosTarea[0]){
                    ?>    

                    <div class="container">
                        <div class="section">
                            <div class="header">Detalles</div>
                            <div class="content">
                                <p><strong>Título:</strong> <?php echo htmlspecialchars($titulo); ?></p>
                                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($descripcion); ?></p>
                                <p><strong>Estado:</strong> <?php echo htmlspecialchars($estado); ?></p>
                                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($username); ?></p>
                            </div>
                        </div>
                        
                        <div class="section"> 
                            <div class="attachment">Archivos Adjuntos</div>
                            
                            <!-- Tarjeta de cada archivo adjunto -->
                            <div class="content">
                                <?php
                                    // Recorremos el array que contiene los ficheros de la tarea (guardado en índice 1 de la variable)
                                    foreach ($ficherosTarea[1] as $fichero) {                                       
                                ?>
                                
                                    <h5 class="card-title"><?php echo $fichero->getNombre(); ?> </h5>
                                    <p class="card-text text-muted text-truncate"><?php echo $fichero->getDescripcion(); ?></p>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo $fichero->getFile(); ?>" class="btn btn-sm btn-outline-primary" download>Descargar</a>
                                        <a href="../ficheros/borrarFichero.php?id=<?php echo $fichero->getId(); ?>" class="btn btn-sm btn-outline-danger">Eliminar</a>
                                    </div>
                            </div>
                            <?php
                                }
                            ?>
                            <!-- Tarjeta subir nuevo archivo -->
                            <div class="content">
                                <a href="../ficheros/subidaFichForm.php?id=<?php echo $id_tarea; ?>" class="upload-link">
                                    <div class="upload-box">Añadir nuevo archivo</div>
                                </a>
                            </div>
                        </div>

                    <?php // Aquí cerramos con php los if abiertos arriba, que muestran html si hay tarea   
                            } else {
                                // Error listando los archivos de la tarea
                                echo '<div class="alert alert-danger" role="alert">' . $ficherosTarea[1] . '</div>';
                            }
                        } else {
                            // Error recuperando info de tarea
                            echo '<div class="alert alert-danger" role="alert">No se pudo recuperar la información de la tarea.</div>';
                        }
                    } else {
                        // No hay información de esa tarea, no entra por GET
                        echo '<div class="alert alert-danger" role="alert">Debes acceder a través del listado de tareas.</div>';
                    }                      
                    ?>
                </div>
            </main>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); ?>
</body>
</html>