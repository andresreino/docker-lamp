<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Lista de Tareas</h2>
                </div>
                <div class="container">
                    <div class="table">
                        <table class="table table-striped table-hover">
                        <?php
                            include_once('../database.php');
                            include_once('../utils.php');
                            // Si se envía info por la url (opción BUSCADOR DE TAREAS)
                            if(!empty($_GET)){
                                $id = $_GET["id"];
                                // id de usuario siempre va a enviarse, estado es opcional, así que usamos ternario
                                $estado = isset($_GET["estado"]) ? $_GET["estado"] : null;

                                $tareasUsuario = buscarTareasUsuario($id, $estado);

                                mostrarCabeceraTablaTareas();

                                mostrarListadoTareas($tareasUsuario);

                            } else { // Si no se envía nada por url, sólo se pretenden listar las tareas (opción LISTA DE TAREAS)
                                // Si hay tareas devuelve true en índice [0] del array y otro array bidimensional con las tareas en índice [1]
                                $listaTareas = listarTareas();
                                // Si listaTareas[0] devuelve false puede ser porque base de datos no exista u otro error
                                if($listaTareas[0]){
                                    $listaVacia = count($listaTareas[1]) == 0 ? true : false;
                                    // Si devuelve true y la lista no está vacía imprime usuarios.
                                    if (!$listaVacia) {
                                        
                                        mostrarCabeceraTablaTareas();
                                        
                                        mostrarListadoTareas($listaTareas);
                                        
                                    } else if ($listaVacia) {
                                        // Si devuelve true y lista vacía (nos ha devuelto array(0) la función listarTareas())no imprime tampoco la cabecera, sólo mensaje 
                                        echo '<div class="alert alert-warning" role="alert">No existen tareas en la base de datos.</div>';   
                                    } else {
                                        echo '<div class="alert alert-warning" role="alert">' . $listaTareas[1];   
                                    }
                                } else {
                                    // Muestra motivo de error al intentar listar los usuarios de la DB
                                    echo '<div class="alert alert-warning" role="alert">' . $listaTareas[1];
                                }
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