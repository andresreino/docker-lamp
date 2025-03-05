<?php
    session_start();
    require_once('../modelo/pdo.php');
    require_once('../interfaces/FicherosDBImp.php');

    $status = false;
    $messages = array();
    $id_tarea = 0;

    if (!empty($_GET)) {

        $idFichero = $_GET['id'];
        try {
            // Usamos un objeto de la clase FicherosDBImp, que implementa la interfaz FicherosDBInt
            $objetoFicheroDBImp = new FicherosDBImp();
    
            $archivoBuscado = $objetoFicheroDBImp->buscaFichero($idFichero);
            $archivo = [true, $archivoBuscado];
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            $archivo = [false, null];
        }
        
        if (!empty($idFichero) && $archivo[0]) {
            // Seleccionamos el id de la tarea en índice [1] del objeto Fichero devuelto por buscarFicheroDB()
            $id_tarea = $archivo[1]->getIdTarea();
            // Seleccionamos la ruta del objeto
            $ruta = $archivo[1]->getFile();
            // Borramos el archivo de la ruta local (no de la BD)(devuelve T/F)
            $borrado = borrarArchivo($ruta);

            if ($borrado) {
                $borrado = $objetoFicheroDBImp->borraFichero($idFichero);
            } 

            if ($borrado) {
                $status = true;
                array_push($messages, 'Archivo borrado correctamente.');
            } else {
                array_push($messages, 'No se pudo borrar el archivo.');
            }  
        } else {
            array_push($messages, 'No se pudo recuperar la información del archivo.');
        }
    } else {
        array_push($messages, 'Debes acceder a través del listado de tareas.');
    }


    $_SESSION["usuario"]["success"] = $status;
    $_SESSION["usuario"]["messages"] = $messages;
    header("Location: ../tareas/tarea.php?id=" . $id_tarea);

    // Borra el archivo de nuestro almacenamiento local (no de la BD) con la localización (ruta) indicada por parámetro
    function borrarArchivo($archivo) {
        return (file_exists($archivo) && unlink($archivo));
    }
?>

