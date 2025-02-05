<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD4. Tarea DWCS</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

    <body>
        <?php include_once('../vista/header.php'); ?>

        <div class="form-container">
            <h2>Iniciar Sesión</h2>

            <?php
            //Comprobar si se reciben datos
            $redirect = isset($_GET['redirect']) ? true : false;
            $error = isset($_GET['error']) ? true : false;
            $message = isset($_GET['message']) ? $_GET['message'] : null;
            if ($redirect) {
                echo '<p class="error">Debes iniciar sesión para acceder.</p>';
            } elseif ($error) {
                if ($message) {
                    echo '<p class="error">Error: ' . $message . '</p>';
                } else {
                    echo '<p class="error">Usuario y contraseña incorrectos.</p>';
                }
            }
            ?>

            <form action="../controlador/loginAuth.php" method="POST">
                <label for="usuario">Usuario:</label>
                <input name="usuario" id="usuario" type="text" placeholder="Introduce tu username">
                
                <label for="contrasena">Contraseña:</label>
                <input name="contrasena" id="contrasena" type="password" placeholder="Introduce tu contraseña">
                
                <input type="submit" value="Iniciar Sesión">
            </form>
        </div>
    </body>
</html>