        
<?php include_once('../head.php'); ?>
<body>
    <?php include_once('../header.php'); ?>
    <?php include_once('../menu.php'); ?>
    <div class="container mt-5"> 
        <div class="container-fluid">   
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nuevo libro</h2>
                </div>
                <div class="container">
                    <form class="mb-5" action="nuevoLibro.php" method="GET" name="formulario" >
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input class="form-control" type="text" name="titulo" id="titulo" required>
                        </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>  
                </div>
            </main>
        </div>
    </div> 
    <?php include_once('../footer.php'); ?>
</body>
</html>
