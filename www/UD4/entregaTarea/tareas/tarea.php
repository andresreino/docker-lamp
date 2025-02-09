<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
	}		
?>
<!DOCTYPE html>
<!-- Dinámicamente se imprime el valor de la cookie (si existe). Si no coge light por defecto -->
<html lang="es" data-bs-theme="<?php echo isset($_COOKIE['tema']) ? $_COOKIE['tema'] : 'light';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD4. Tarea DWCS</title>
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
                    include_once('../modelo/mysqli.php');

                    if(!empty($_GET)){
                        $id = $_GET["id"];
                        $tarea = buscarTarea($id);
                
                        $titulo = $tarea[1]["titulo"];    
                        $descripcion = $tarea[1]["descripcion"];    
                        $estado = $tarea[1]["estado"];      
                        $username = $tarea[1]["username"];              
                    }                      
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
                        <div class="content">
                            <a href="../ficheros/subidaFichForm.php?id=<?php echo $id; ?>" class="upload-link">
                                <div class="upload-box">Añadir nuevo archivo</div>
                            </a>
                        </div>
                    </div>
                




                    <div class="table">
                        
                    <table class="table table-striped table-hover">
                        <?php
                            include_once('../utils.php');
                            include_once('../modelo/pdo.php');
                            include_once('../modelo/mysqli.php');
                            
                            ?>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); ?>
</body>
</html>