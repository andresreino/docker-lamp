<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">
            <div class="row">
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2>Préstamo de libros</h2>
                    </div>
                    <div class="container">
                    <?php
                    include_once('../pdo.php');
                    include_once('../mysqli.php');
                    include_once('../utils.php');
                    
                    if(!empty($_POST)){
                        $id_usuario = $_POST["id_usuario"];
                        $id_libro = $_POST["id_libro"];
                    }                   
                    $usuarios = mostrarUsuarios();
                    $usuarioValido = false;

                    foreach ($usuarios as $usuario) {
                       $id = $usuario["id"];
                       if($id == $id_usuario){
                        $usuarioValido = true;
                        break;
                       }
                    }

                    if($usuarioValido){
                        $resultado = prestarLibro($id_usuario, $id_libro);
                        mostrarResultado($resultado);
                    } else {
                        echo '<div class="alert alert-warning" role="alert">El usuario no está registrado en la base de datos</div>';
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


