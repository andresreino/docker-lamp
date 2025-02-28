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

    include_once('../modelo/pdo.php');
    include_once('../utils.php');
    if (!empty($_GET)){
        $id = $_GET['id'];
        $resultado = borrarUsuario($id);
        
        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // Con header redirigimos a la página que nos solicitó desde el form usando http_referer
        $referer = $_SERVER['HTTP_REFERER'];
        header('Location: ' . $referer . '?success=' . $resultado[0] . '&message=' . $resultado[1]);
        exit();
    }                      
?>  