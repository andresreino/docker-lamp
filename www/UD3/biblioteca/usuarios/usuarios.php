<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Lista de usuarios</h2>
                </div>

                <h2>Usuarios</h2>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">    
                    <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Localidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    include_once('../pdo.php');

                    $usuarios = mostrarUsuarios();

                    foreach ($usuarios as $usuario) {
                        echo '<tr>';
                        echo '<td>' . $usuario["id"]. '</td>';
                        echo '<td>' . $usuario["nombre"]. '</td>';
                        echo '<td>' . $usuario["apellidos"]. '</td>';
                        echo '<td>' . $usuario["localidad"]. '</td>';
                        echo '</tr>';
                    }
                    ?>             
                    </tbody>
                </table>
            </main>
        </div
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>
