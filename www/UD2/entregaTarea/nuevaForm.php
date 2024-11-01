<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <?php include_once('menu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Crear nueva tarea</h2>
                </div>
                <div class="container">
                    <form class="mb-5" action="nueva.php" method="POST" name="formulario" >
                        <div class="mb-3">
                            <label class="form-label">Identificador</label>
                            <input class="form-control" type="text" name="id" id="id" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" >Descripción</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion">
                        </div>
                        <div class="mb-3">
                            <label for="estado">Estado</label>
                            <select class="form-select" name="estado" id="estado" >
                                <option value="pendiente">Pendiente</option>
                                <option value="enproceso">En proceso</option>
                                <option value="completada">Completada</option>
                            </select> 
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>  
                </div>
            </main>
        </div>
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>