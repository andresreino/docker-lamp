<?php
session_start();
require_once('../modelo/pdo.php');

$status = false;
$messages = array();
$id_tarea = 0;

if (!empty($_GET))
{
    $idFichero = $_GET['id'];
    // Método devuelve array con true/false en índice [0] y array con resultado (si true) o mensaje (si false)
    $archivo = buscarFicheroDB($idFichero);
    
    if (!empty($idFichero) && $archivo[0]) {
        // Seleccionamos el id de la tarea en índice [1] del array devuelo por buscarFicheroDB()
        $id_tarea = $archivo[1]['id_tarea'];
        
        $ruta = $archivo[1]['file'];
        $borrado = borrarArchivo($ruta);
        if ($borrado) $borrado = borrarFicheroDB($archivo[1]['id']);

        if ($borrado)
        {
            $status = true;
            array_push($messages, 'Archivo borrado correctamente.');
        }
        else
        {
            array_push($messages, 'No se pudo borrar el archivo.');
        }
        
    }
    else
    {
        array_push($messages, 'No se pudo recuperar la información del archivo.');
    }
}
else
{
    array_push($messages, 'Debes acceder a través del listado de tareas.');
}


$_SESSION["usuario"]["success"] = $status;
$_SESSION["usuario"]["messages"] = $messages;
header("Location: ../tareas/tarea.php?id=" . $id_tarea);

// Borra el archivo de nuestro almacenamiento local (no de la BD) con la localización (ruta) indicada por parámetro
function borrarArchivo($archivo)
{
    return (file_exists($archivo) && unlink($archivo));
}

