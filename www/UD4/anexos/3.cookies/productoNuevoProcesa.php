<?php include_once('head.php'); ?>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col">
                
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nuevo usuario</h2>
                </div>

                <div class="container justify-content-between">
                    <?php
                    $nombre = $_POST['nombre'];
                    $descripcion = $_POST['descripcion'];
                    $precio = $_POST['precio'];
                    $unidades = $_POST['unidades'];
                    $foto = $_FILES['foto'];
                    require_once('utils.php');
                    $error = false;
                    if (!validarCampoTexto($nombre))
                    {
                        $error = true;
                        echo '<div class="alert alert-danger" role="alert">El campo nombre es obligatorio y debe contener al menos 3 caracteres.</div>';
                    }
                    if (!validarCampoTexto($descripcion))
                    {
                        $error = true;
                        echo '<div class="alert alert-danger" role="alert">El campo descripcion es obligatorio y debe contener al menos 3 caracteres.</div>';
                    }
                    if (!esNumeroValido($precio))
                    {
                        $error = true;
                        echo '<div class="alert alert-danger" role="alert">El campo precio es obligatorio y debe contener solo números.</div>';
                    }
                    if (!esNumeroValido($unidades))
                    {
                        $error = true;
                        echo '<div class="alert alert-danger" role="alert">El campo unidades es obligatorio y debe contener solo números.</div>';
                    }
                    if (isset($foto))
                    {
                        $foto = $_FILES['foto'];
                        $size = $foto['size'];
                        $type = strtolower(pathinfo(basename($foto['name']), PATHINFO_EXTENSION));

                        if ($size > 5000000) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El tamaño máxima de archivo es de 5.</div>';
                        }
                        else if($type != "jpg" && $type != "png")
                        {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El formato de imágen debe ser JPG o PNG.</div>';
                        }
                    }
                    else
                    {
                        $error = true;
                        echo '<div class="alert alert-danger" role="alert">Debes subir un fichero de imágen para el producto.</div>';
                    }
                    if (!$error)
                    {
                        require_once('database.php');
                        $resultado = nuevoProducto($nombre, $descripcion, $precio, $unidades, file_get_contents($foto['tmp_name']));
                        if ($resultado[0])
                        {
                            echo '<div class="alert alert-success" role="alert">Producto guardado correctamente.</div>';
                        }
                        else
                        {
                            echo '<div class="alert alert-danger" role="alert">Ocurrió un error guardando el producto: ' . $resultado[1] . '</div>';
                        }
                    }
                    ?>
                </div>

                <?php include_once('back.php'); ?>

            </main>
        </div>
    </div>
    
    <?php include_once('footer.php'); ?>
    
</body>
</html>
