<?php
    session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
    if(!isset($_SESSION['usuario'])){	
        header("Location: vista/login.php?redirigido=true");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $idTarea = $_GET['id'];
        $nombreFichero = $_POST['nombreFichero'];
        $descripcion = $_POST['descripcion'];
    }

    include_once("../modelo/pdo.php");
    $target_dir = "files/";
    //Recuepramos el nombre del fichero y lo concatenamos a la ruta de la carpeta donde se guardará
    $target_file = $target_dir . basename($_FILES["fichero"]["name"]);
    //Obtenemos el tipo del fichero (su extensión)
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //is_writable($target_dir);

    function procesarSubidaFicheroServidor($target_dir, $target_file, $fileType) {
        // Comprobamos que no exista previamente
        if(!file_exists($target_file)){
            // Comprobamos que no sea mayor a 20MB
            if($_FILES["fichero"]["size"] <= 20000000){
                // Comprobamos que sea formato admitido
                if($fileType == 'jpg' || $fileType == 'png' || $fileType == 'pdf'){
                    // Subimos archivo al servidor
                    if(move_uploaded_file($_FILES["fichero"]["tmp_name"], $target_file)){
                        return [true, "El fichero " . htmlspecialchars(basename($_FILES["fichero"]["name"])) . " ha sido subido."];
                    } else {
                        return [false, "Ha ocurrido un error subiendo el archivo."];
                    }
                } else {
                    return [false, "Sólo están permitidos los formatos jpg, png o pdf."];
                }                     
            } else {
                return [false, "El archivo es demadiado grande."];
            }
        } else {
            return [false, "El archivo ya existe en el servidor."];
        }
    }

    procesarSubidaFicheroServidor($target_dir, $target_file, $fileType);
    guardarFicheroBD($nombreFichero, $target_file, $descripcion, $idTarea);
    
    // Redirigimos a la página desde la que se llamó a esta
    header('Location: ' . $_SERVER['HTTP_REFERER']); 
    exit();
?>