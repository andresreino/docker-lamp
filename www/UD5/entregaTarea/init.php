<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
	}
    // Sólo admin puede acceder a esta página. Si usuario intenta acceder escribiendo la url lo redirigimos a index
    if($_SESSION['usuario']['rol'] != 1){
        header("Location: index.php");
    }		
?>
<?php include_once('head.php'); ?>
<body>
    <?php include_once('vista/header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('vista/menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Menú</h2>
                </div>
                <div class="container">
                <?php
                include_once('utils.php');
                include_once('usuarios/Usuario.php');
                include_once('tareas/Tarea.php');
                include_once('modelo/mysqli.php');
                
                // CrearDB() devuelve array con 2 valores: [0]=true/false [1]=Texto explicativo(BD creada, ya existe o error)
                $creacionDB = crearDB("tareas");
                
                mostrarResultado($creacionDB);
                
                // Imprime el código del índice 1 de la variable, según haya sido true o false
                
                $creacionTablaUsuarios = crearTablaUsuarios();
                
                mostrarResultado($creacionTablaUsuarios);
                
                
                $creacionTablaTareas = crearTablaTareas();
                
                mostrarResultado($creacionTablaTareas);
                
                
                $creacionTablaFicheros = crearTablaFicheros();
                
                mostrarResultado($creacionTablaFicheros);
                

                ?>
                </div>
            </main>
        </div>
    </div>
    <?php include_once('vista/footer.php'); ?>
</body>
</html>