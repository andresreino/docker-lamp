<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
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
                    <h2>Gestión de tarea</h2>
                </div>
                <div class="container">
                <?php
                if (!empty($_POST)){
                    include_once('../utils.php');
                    include_once('../modelo/pdo.php');
                    
                    $id = $_POST["id"];
                    $nombre = $_POST["nombre"];
                    $apellidos = $_POST["apellidos"];
                    $username = $_POST["username"];
                    $rol = $_POST["rol"];
                    // Si usuario no actualiza contrasena sistema le asigna cadena vacía "" por defecto
                    $contrasena = $_POST["contrasena"];
                    
                    $resultado = editarUsuario($id, $nombre, $apellidos, $username, $rol, $contrasena);
                    
                    mostrarResultado($resultado);
                }
                ?>
                </div>
            </main>
        </div>
    </div>
    <?php include_once('../vista/footer.php'); ?>
</body>
</html>