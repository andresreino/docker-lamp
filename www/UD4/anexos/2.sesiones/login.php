<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y sesiones</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
        
        <div class="form-container">
            <h2>Iniciar Sesión</h2>

            <?php
            //Comprobar si se reciben datos
            $redirect = isset($_GET['redirect']) ? true : false;
            $error = isset($_GET['error']) ? true : false;
            $message = isset($_GET['message']) ? $_GET['message'] : null;
            if ($redirect)
            {
                echo '<p class="error">Debes iniciar sesión para acceder.</p>';
            }
            elseif ($error)
            {
                if ($message)
                {
                    echo '<p class="error">Error: ' . $message . '</p>';
                }
                else
                {
                    echo '<p class="error">Usuario y contraseña incorrectos.</p>';
                }
            }
            ?>

            <form action="auth.php" method="POST">
                <label for="usuario">Usuario:</label>
                <input name="usuario" id="usuario" type="text" placeholder="Introduce tu usuario" required>
                
                <label for="pass">Contraseña:</label>
                <input name="pass" id="pass" type="password" placeholder="Introduce tu contraseña" required>
                
                <input type="submit" value="Iniciar Sesión">
            </form>
        </div>
    </body>
</html>