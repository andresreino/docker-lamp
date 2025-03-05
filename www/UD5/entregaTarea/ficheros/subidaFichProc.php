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
    include_once("../utils.php");
    include_once("Fichero.php");
    include_once("../interfaces/FicherosDBImp.php");
    
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

    // Usamos las constantes de la clase Fichero 
    $tipoPermitido = Fichero::FORMATOS; //Tipos de ficheros permitidos 
    $tamanoMaximoArchivo = Fichero::MAX_SIZE;

    function procesarSubidaFicheroServidor($target_file, $tipoPermitido, $rutaFinalArchivo, $tamanoMaximoArchivo) {
        // Comprobamos que no exista previamente
        if(!file_exists($target_file)){
            // Comprobamos que no sea mayor a 20MB
            if($_FILES["fichero"]["size"] <= $tamanoMaximoArchivo){
                // Comprobamos que sea formato admitido comparándolo con type de variable superglobal
                if(in_array($_FILES['fichero']['type'], $tipoPermitido)){
                // Otra forma, aunque no tan segura, ya que podrían cambiar tipo de archivo
                //if($fileType == 'jpg' || $fileType == 'png' || $fileType == 'pdf'){
                    // Subimos archivo al servidor
                    if(move_uploaded_file($_FILES["fichero"]["tmp_name"], $rutaFinalArchivo)){
                        return [true, "El fichero ha sido almacenado en local correctamente."];
                    } else {
                        return [false, "Ha ocurrido un error subiendo el archivo."];
                    }
                } else {
                    return [false, ['formato' => "Sólo están permitidos los formatos jpg, png o pdf."]];
                }                     
            } else {
                return [false, "El archivo es demadiado grande."];
            }
        } else {
            return [false, "El archivo ya existe en el servidor."];
        }
    }

    $resultado = procesarSubidaFicheroServidor($target_file, $tipoPermitido, $rutaFinalArchivo, $tamanoMaximoArchivo);

    if($resultado[0]) {
        $fichero = new Fichero($nombreFichero, $rutaFinalArchivo, $descripcion, $idTarea);
        // Usamos método estático validar de la clase Fichero
        $validacionFichero = Fichero::validar($fichero);
        
        // Si fichero valida, devuelve array vacío
        if(empty($validacionFichero)){
            // Usamos un objeto de la clase FicherosDBImp, que implementa la interfaz FicherosDBInt
            $objetoFicheroDBImp = new FicherosDBImp();
            try {
                // Empleamos un método de esta clase para guardar el fichero en la DB (devuelve T/F)
                // Para que nos muestre error si método no funciona recogemos mensaje de la excepción que lanza
                $exitoSubidaArchivo = $objetoFicheroDBImp->nuevoFichero($fichero);
                // Si correcto devuelve true, si algo falla devuelve excepción
                $resultado[0] = $exitoSubidaArchivo;
                // Si todo correcto, introducimos mensaje en índice 1
                $resultado[1] = "Fichero guardado correctamente en la BD.";
            } catch (Exception $e) {
                $error = $e->getMessage();
                $resultado[0] = false;
                $resultado[1] += $error;
            }

            // Puede producirse error al guardar fichero (ej conexión BD), así que borramos
            // archivo del almacenamiento local hecho previamente si $resultado[0] es false 
            if(!$resultado[0]) {
                borrarArchivoLocal($rutaFinalArchivo);
                // Si hay algún error introducimos el mensaje de la excepción en índice 1
            }
        
        } else { // Si no valida, devuelve array con errores
            // Lo borramos del almacenamiento local (se ha almacenado al usar procesarSubidaFicheroServidor())
            borrarArchivoLocal($rutaFinalArchivo);
            $resultado[0] = false; 
            // Guardamos errores de validación en índice 1 del array $resultado
            $resultado[1] = $validacionFichero;
        }
    }

    function borrarArchivoLocal($ruta){
        if(file_exists($ruta)){
            unlink($ruta);
         }
    }
    
    // Creamos 2 variables de sesión. En la primera guarda 0 (false) o 1 (true)
    $_SESSION["usuario"]["success"] = $resultado[0];
    // Guardamos mensaje que nos devuelve función rocesarSubidaFicheroServidor (está en índice 1 del array que devuelve)
    $_SESSION["usuario"]["messages"] = $resultado[1];
    
    if($resultado[0]){
        // Redirigimos a la página tarea.php pasando en la URL el id de tarea correspondiente
        header('Location: ../tareas/tarea.php?id=' . $idTarea); 
    } else {
        // Si false, redirigimos al formulario para que muestre allí los errores
        // En esta variable guardamos la url desde la que se nos solicitó ejecutar este código
        // La limpiamos primero usando esta función por si trae mensajes (?success= u otros)
        $refererLimpio = limpiarReferer($_SERVER['HTTP_REFERER']);
        header('Location: ' . $refererLimpio . '?id=' . $idTarea); 
    }
    exit();
?>