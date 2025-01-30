<?php include_once('head.php'); ?>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col">
                
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nuevo productos</h2>
                </div>

                <div class="container justify-content-between">
                    
                    <?php
                    require_once('database.php');
                    $producto = buscaProducto($_GET['id']);

                    if ($producto[0])
                    {
                        $producto = $producto[1];
                        echo '<p>' . $producto['nombre'] . '</p>';
                        $foto = $producto['foto'];
                        if ($foto)
                        {
                            // Convertir datos binarios a base64
                            $fotoBase64 = base64_encode($foto);
                            // Crear la URL de datos
                            $mimeType = "image/jpeg"; // Cambia esto si necesitas soportar otros formatos
                            $fotoUrl = "data:$mimeType;base64,$fotoBase64";

                            // Mostrar la imagen en HTML
                            echo "<img src='$fotoUrl' alt='Imagen del producto'>";
                        }
                    }
                    else
                    {
                        echo '<div class="alert alert-danger" role="alert">Ocurrió un error recuperando el producto: ' . $producto[1] . '</div>';
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
