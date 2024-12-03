<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('../menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Lista de usuarios</h2>
                </div>
                <div class="container">
                <div class="table">
                        <table class="table table-striped table-hover">
                            <?php
                            include_once('../database.php');
                            // Si hay usuarios devuelve true en índice 0 del array y otro array asociativo con los usuarios en índice 1
                            $listaUsuarios = listarUsuariosPDO();
                            // Si listaUsuarios[0] devuelve false puede ser porque base de datos no exista u otro error
                            if($listaUsuarios[0]){
                                $listaVacia = count($listaUsuarios[1]) == 0 ? true : false;
                                // Si la lista no está vacía imprime usuarios.
                                if (!$listaVacia) {
                                    echo '<thead class="thead">';
                                    echo '<tr>';                            
                                    echo '<th>ID</th>';
                                    echo '<th>Nombre</th>';
                                    echo '<th>Apellidos</th>';
                                    echo '<th>Usuario</th>';
                                    echo '<th></th>'; // Header para los botones 
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';                                   
                                    
                                    foreach ($listaUsuarios[1] as $usuario) {                                       
                                        echo '<tr>';
                                        echo '<td>' . $usuario["id"] . '</td>';   
                                        echo '<td>' . $usuario["nombre"] . '</td>';   
                                        echo '<td>' . $usuario["apellidos"] . '</td>';   
                                        echo '<td>' . $usuario["username"] . '</td>';
                                        echo '<td>';
                                        // Usamos <a> para incluir los botones y que nos lleven a cada php correspondiente (incluir role="button")
                                        // Incluimos el id del usuario en la url al hacer click en el botón
                                        echo '<a class="btn btn-info" href="editaUsuarioForm.php?id=' . $usuario["id"] . '" role="button">Editar</a> '; 
                                        echo '<a class="btn btn-danger" href="borraUsuario.php?id=' . $usuario["id"] . '" role="button">Borrar</a>';   
                                        echo '</td>';
                                        echo '</tr>';        
                                    }
                                    echo '</tbody>';
                                } else if ($listaVacia) {
                                    // Si devuelve true y lista vacía no imprime tampoco la cabecera, sólo mensaje 
                                    echo '<div class="alert alert-warning" role="alert">No existen usuarios en la base de datos.</div>';   
                                } else {
                                    echo '<div class="alert alert-warning" role="alert">' . $listaUsuarios[1];   
                                }
                            } else {
                                // Muestra motivo de error al intentar listar los usuarios de la DB
                                echo '<div class="alert alert-warning" role="alert">' . $listaUsuarios[1];   
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>