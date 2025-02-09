<?php 
	session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
	if(!isset($_SESSION['usuario'])){	
		header("Location: vista/login.php?redirigido=true");
	}
    // Sólo admin puede acceder a esta página. Si usuario intenta acceder escribiendo la url lo redirigimos a index
    if($_SESSION['usuario']['rol'] != 1){
        header("Location: ../index.php?redirigido=true");
    }		

     
    if (!empty($_POST)){
        include_once('../utils.php');
        include_once('../modelo/pdo.php');
        
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $username = $_POST["username"];
        $rol = $_POST["rol"];
        // Si usuario no actualiza contrasena sistema le asigna cadena vacía "" por defecto
        $contrasena = $_POST["contrasena"];
        
        $resultado = editarUsuario($id, $nombre, $apellidos, $username, $rol, $contrasena);

        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // Con header redirigimos a la página que nos solicitó desde el form usando http_referer
        $referer = $_SERVER['HTTP_REFERER'];

        // OJO, en este header tenemos que poner & delante de succese (no ?) ya que
        // $referer ya va a incluir "?" en la url, puesto que devuelve también el id (ej: ?id=3)
        header('Location: ' . $referer . '&success=' . $resultado[0] . '&message=' . $resultado[1]);
        exit();
        
    }
?>
                