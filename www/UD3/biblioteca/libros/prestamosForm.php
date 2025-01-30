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
                    <!-- Tabla de libros -->
                    <div class="container">
                        <form class="mb-5" action="prestamos.php" method="POST" name="formulario" >    
                            <div class="mb-3">
                                <label class="form-label">Id de usuario</label>
                                <input class="form-control" type="number" name="id_usuario" id="id_usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="estado">Seleccione un libro</label>
                                <select class="form-select" name="id_libro" id="id_libro" required>
                                    <!-- disabled: opción no válida al enviar form ## selected: opción visible al cargar -->
                                    <option value="" disabled selected>Seleccione un título</option>
                                    <?php
                                    include_once('../mysqli.php');
                                    $libros = mostrarLibrosDisponibles();

                                    foreach ($libros as $libro) {
                                        $titulo = $libro["titulo"];
                                        $id_libro = $libro['id'];
                                        echo "<option value=$id_libro>$titulo</option>";   
                                    }
                                    ?>
                                </select> 
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>  
                    </div> 
                </main>
            </div>
        </div> 
    </div>
    <?php include_once('../footer.php'); ?>
</body>
</html>
