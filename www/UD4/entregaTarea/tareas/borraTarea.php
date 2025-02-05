<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
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
                    <h2>Gestión de tarea</h2>
                </div>
                <div class="container">
                    <div class="table">
                        <table class="table table-striped table-hover">
                        <?php
                            include_once('../utils.php');
                            include_once('../modelo/mysqli.php');

                            if(!empty($_GET)){
                                $id = $_GET["id"];
                                $resultado = borrarTarea($id);
                                mostrarResultado($resultado);
                            }
                            ?>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); ?>
</body>
</html>