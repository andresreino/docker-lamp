        
<?php include_once('head.php'); ?>
<body>
    <?php include_once('header.php'); ?>
    <?php include_once('menu.php'); ?>
    <div class="container mt-5"> 
        <!-- Tabla de libros -->
        <h2>Libros</h2>
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
                include_once('mysqli.php');
                $libros = mostrarLibros();

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
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>
