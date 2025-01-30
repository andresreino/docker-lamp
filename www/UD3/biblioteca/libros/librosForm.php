        
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">
            <div class="row">
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2>Libros de la biblioteca San Clemente</h2>
                    </div>
                    <!-- Tabla de libros -->
                    <div class="container">
                        <form class="mb-5" action="libros.php" method="GET" name="formulario" >    
                            <div class="mb-3">
                                <label for="estado">Estado</label>
                                <select class="form-select" name="estado" id="estado" required>
                                    <!-- disabled: opción no válida al enviar form ## selected: opción visible al cargar -->
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="todos">Todos</option>
                                    <option value="disponible">Disponible</option>
                                    <option value="prestado">Prestado</option>
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
