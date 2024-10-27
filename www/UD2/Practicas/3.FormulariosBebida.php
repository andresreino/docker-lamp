<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DWCS UD2. Boletín 3.</title>
</head>
<body>
    <h1>Anexo 3. Formularios</h1>

    <h3>Tarea 2. Envío de formularios</h3>

    <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $bebida = $_POST["Bebida"];
            $cantidad = $_POST["Cantidad"];
            $total = 0;

            if($bebida == "Coca Cola"){
                $total = $cantidad * 1;
            }elseif ($bebida == "Pepsi Cola") {
                $total = $cantidad * 0.8;
            }elseif ($bebida == "Fanta Naranja") {
                $total = $cantidad * 0.9;
            }else {
                $total = $cantidad * 1.1;
            }

            echo "Pediste $cantidad botellas de $bebida. Precio total a pagar: $total Euros.";
        }
    ?>
    <br><br>
    <a href="3.Formularios.php" class="btn btn-info">Volver</a>

</body>
</html>