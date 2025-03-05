<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}

    if (!empty($_POST)){
        include_once('../utils.php');
        include_once('../modelo/mysqli.php');
        include_once('../modelo/pdo.php');
        include_once('Tarea.php');
        
        $idTarea = $_POST["id"];
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $estado = $_POST["estado"];
        $username = $_POST["username"];
        
        // Para UD5 cambiamos y hacemos la validación de tarea aquí, no en mysqli.php dentro de la función guardarTarea()
        // En esta variable guardamos array con booleano true/false[0] + mensaje[1]
        $tareaValida = tareaEsValida($titulo, $descripcion, $estado, $username);
        
        // Necesitamos id del usuario para crear objeto Tarea. Devuelve T/F índice 0 y objeto en índice 1
        $usuario = buscarUsuarioPorUsername($username);
        $idUsuario = $usuario[1]->getId();
 
        if($tareaValida[0]){
            // Si tarea es válida creamos nuevo objeto Tarea
            $tarea = new Tarea($titulo, $descripcion, $estado, $idUsuario);
            // Le añadimos al objeto su id (no se hace en constructor)
            $tarea->setId($idTarea);
            // Devuelve T/F dependiendo de si se ha guardado o ha habido algún error
            $resultado = editarTarea($tarea);

        } else {
            // Tarea no es válida. En variable guardamos False (índice 0) y mensaje de error (índice 1)
            $resultado = $tareaValida;
        }

      // En esta variable de sesión guardamos mensaje que nos devuelve función guardarTarea (está en índice 1 del array que devuelve)
        $_SESSION["usuario"]["success"] = $resultado[0];
        $_SESSION["usuario"]["messages"] = $resultado[1];

        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // Con header redirigimos a la página que nos solicitó desde el form usando http_referer        
        $referer = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $referer);
        exit();    
    }
?>
                        