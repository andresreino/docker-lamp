<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Gestión de tarea</h2>
                </div>
                <div class="container">
                    <div class="table">
                        <table class="table table-striped table-hover">
                        <?php
                        if (!empty($_POST)){
                            include_once('../utils.php');
                            include_once('../database.php');
                            
                            $id = $_POST["id"];
                            $titulo = $_POST["titulo"];
                            $descripcion = $_POST["descripcion"];
                            $estado = $_POST["estado"];
                            //$id_usuario = $_POST["id_usuario"];
                            $username = $_POST["username"];
                            
                            $resultado = editarTarea($id, $titulo, $descripcion, $estado, $username);
                            
                            mostrarResultado($resultado);
                        }
                        ?>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>