<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
	}

    include_once('../utils.php');
    include_once('../modelo/mysqli.php');

    if(!empty($_GET)){
        $id = $_GET["id"];
        $resultado = borrarTarea($id);

        // En esta variable de sesión guardamos mensaje que nos devuelve función guardarTarea (está en índice 1 del array que devuelve)
        $_SESSION["usuario"]["success"] = $resultado[0];
        $_SESSION["usuario"]["message"] = $resultado[1];

        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // Con header redirigimos a la página que nos solicitó desde el form usando http_referer        
        $referer = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $referer);
        exit();    
    }
?>
                        