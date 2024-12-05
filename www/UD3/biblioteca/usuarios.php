<?php include_once('head.php'); ?>
<body>
    <?php include_once('header.php'); ?>
    <?php include_once('menu.php'); ?>
    <div class="container mt-5"> 
        <!-- Tabla de libros -->
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
            include_once('pdo.php');

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
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>
