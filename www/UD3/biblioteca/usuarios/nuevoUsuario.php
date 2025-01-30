        
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">
            <div class="row">
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2>Gestión de usuario</h2>
                    </div>
                    <div class="container">
                    <?php
                    if (!empty($_POST)){
                        include_once('../pdo.php');
                        include_once('../utils.php');
                        
                        $nombre = $_POST["nombre"];
                        $apellidos = $_POST["apellidos"];
                        $localidad = $_POST["localidad"];

                        $resultado = guardarUsuario($nombre, $apellidos, $localidad);
                    
                        mostrarResultado($resultado);
                    }
                    ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
  
    <?php include_once('../footer.php'); ?>
</body>
</html>
