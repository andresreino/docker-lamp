<?php
    session_start();
    // Si no hay sesión iniciada se redirige a login para que introduzca username y contraseña
    if(!isset($_SESSION['usuario'])){	
        header("Location: ../vista/login.php?redirigido=true");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $idTarea = $_GET['id'];
        $nombreFichero = $_POST['nombreFichero'];
        $descripcion = $_POST['descripcion'];
    }

    include_once("../modelo/pdo.php");
    $target_dir = "../files/";
    //Recuepramos el nombre del fichero y lo concatenamos a la ruta de la carpeta donde se guardará
    $target_file = $target_dir . basename($_FILES["fichero"]["name"]);
    //Obtenemos el tipo del fichero (su extensión)
    $fileType = strtolower(pathinfo($_FILES['fichero']['name'], PATHINFO_EXTENSION));
    //$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Generamos código aleatorio para modificar nombre del archivo y que no coincida
    $codigoAleatorio = bin2hex(random_bytes(8)); // 16 caracteres alfanuméricos
    // Creamos la ruta final del archivo concatenando lo anterior
    $rutaFinalArchivo = $target_dir . $codigoAleatorio . '.'. $fileType;

    $tipoPermitido = ['image/jpeg', 'image/png', 'application/pdf']; //Tipos de ficheros permitidos 



    function procesarSubidaFicheroServidor($target_file, $tipoPermitido, $rutaFinalArchivo) {
        // Comprobamos que no exista previamente
        if(!file_exists($target_file)){
            // Comprobamos que no sea mayor a 20MB
            if($_FILES["fichero"]["size"] <= 20000000){
                // Comprobamos que sea formato admitido comparándolo con type de variable superglobal
                if(in_array($_FILES['fichero']['type'], $tipoPermitido)){
                // Otra forma, aunque no tan segura, ya que podrían cambiar tipo de archivo
                //if($fileType == 'jpg' || $fileType == 'png' || $fileType == 'pdf'){
                    // Subimos archivo al servidor
                    if(move_uploaded_file($_FILES["fichero"]["tmp_name"], $rutaFinalArchivo)){
                        return [true, "El fichero ha sido subido correctamente."];
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

    $resultado = procesarSubidaFicheroServidor($target_file, $tipoPermitido, $rutaFinalArchivo);

    if($resultado[0]) {
        guardarFicheroBD($nombreFichero, $rutaFinalArchivo, $descripcion, $idTarea);
    }
    
    // Creamos 2 variables de sesión. En la primera guarda 0 (false) o 1 (true)
    $_SESSION["usuario"]["success"] = $resultado[0];
    // Guardamos mensaje que nos devuelve función rocesarSubidaFicheroServidor (está en índice 1 del array que devuelve)
    $_SESSION["usuario"]["messages"] = $resultado[1];
    
    // Redirigimos a la página tarea.php con id de tarea correspondiente
    header('Location: ../tareas/tarea.php?id=' . $idTarea); 
    exit();
?>