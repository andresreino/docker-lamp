<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}
    // Sólo admin puede acceder a esta página. Si usuario intenta acceder escribiendo la url lo redirigimos a index
    if($_SESSION['usuario']['rol'] != 1){
        header("Location: ../index.php?redirigido=true");
    }		

    if (!empty($_POST)){
        include_once('../utils.php');
        include_once('../modelo/pdo.php');
        
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $username = $_POST["username"];
        $rol = $_POST["rol"];
        $contrasena = $_POST["contrasena"];

        require_once('Usuario.php');

        $usuario = new Usuario($username, $nombre, $apellidos, $rol, $contrasena);

        $errores = $usuario->validar();

        $resultado = [];

        if(!empty($errores)){
            $resultado = [false, "Ha ocurrido un error con los datos del usuario."];
            $_SESSION["usuario"]["errors"] = $errores;
        } else {
            $resultado = guardarUsuario($usuario);
        }

        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // La limpiamos primero usando esta función por si trae mensajes (?success= u otros)
        $refererLimpio = limpiarReferer($_SERVER['HTTP_REFERER']);
        
        // Con header redirigimos a la página que nos solicitó desde el form usando http_referer
        header('Location: ' . $refererLimpio . '?success=' . $resultado[0] . '&message=' . $resultado[1]);
        exit();
    }
?>
                