        
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">   
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Libros</h2>
                </div>
    
                <!-- Tabla de libros -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha de Préstamo</th>
                            <th>Disponible</th>
                            <th>ID Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include_once('../mysqli.php');

                        if(!empty($_GET)){

                            $estado = $_GET["estado"];
                        }

                        if($estado === "todos"){
                            $libros = mostrarLibros();

                        } elseif ($estado === "prestado") {
                            $libros = mostrarLibrosNoDisponibles();
                        } else {
                            $libros = mostrarLibrosDisponibles();
                        }

                        foreach ($libros as $libro) {
                            
                            echo '<tr>';
                            echo '<td>' . $libro["id"]. '</td>';
                            echo '<td>' . $libro["titulo"]. '</td>';
                            // Convertir la fecha al formato español
                            $fecha = $libro["fecha_prestamo"];
                            echo '<td>' . ($fecha == null ? "-" : date("d-m-Y", strtotime($fecha))) . '</td>';
                            echo '<td>' . ($libro["disponible"] ? '✅' : '❌') . '</td>';
                            echo '<td>' . ($libro["id_usuario"] == null ? "-" : $libro["id_usuario"]). '</td>';
                            echo '</tr>';
                        }
                        /* Podemos incluir emojis directamente o por código unicode. En emojipedia, p ej, aparece el código unicode
                        hexadecimal (U+2705) y lo habría que convertir a decimal: 2075 sería 9989. Por lo tanto también podríamos usar:
                        echo '<td>' . ($libro["disponible"] ? '&#9989;' : '&#10060;') . '</td>';
                        */
                        ?>
                    </tbody>
                </table>
            </main>
        </div>    
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>
