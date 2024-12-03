<?php include_once('head.php'); ?>
<body>
    <?php include_once('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Menú</h2>
                </div>
                <div class="container">
                <?php
                include_once('database.php');
                // CrearDB() devuelve array con 2 valores: [0]=true/false [1]=Texto explicativo(BD creada, ya existe o error)
                $creacionDB = crearDB("tareas");
                
                mostrarResultado($creacionDB);
                
                // Imprime el código del índice 1 de la variable, según haya sido true o false
                echo "</div>";
                $creacionTablaUsuarios = crearTablaUsuarios();
                
                mostrarResultado($creacionTablaUsuarios);
                
                echo "</div>";
                $creacionTablaTareas = crearTablaTareas();
                
                mostrarResultado($creacionTablaTareas);
                ?>
                </div>
            </main>
        </div>
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>