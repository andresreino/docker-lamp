<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: ../vista/login.php?redirigido=true");
	}

    include_once("../utils.php");
    include_once('../modelo/mysqli.php');

    if(!empty($_POST)){
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $estado = $_POST["estado"];
        $username = $_POST["username"];
    }
    
    $resultado = guardarTarea($titulo, $descripcion, $estado, $username);

    // Creamos 2 variables de sesión. En la primera guarda 0 (false) o 1 (true)
    $_SESSION["usuario"]["success"] = $resultado[0];
    // Guardamos mensaje que nos devuelve función guardarTarea (está en índice 1 del array que devuelve)
    $_SESSION["usuario"]["messages"] = $resultado[1];

    // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
    // Con header redirigimos a la página que nos solicitó desde el form usando http_referer        
    $referer = $_SERVER['HTTP_REFERER'];
    header('Location: ' . $referer);
    exit();             
?>
                