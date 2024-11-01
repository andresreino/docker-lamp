<?php 

$tareas = [
    ['Identificador' => 001,
    'Descripcion' => " Compra\r balo<nes ",
    'Estado' => "Pendiente"],
    ['Identificador' => 002,
    'Descripcion' => "Lavar coche",
    'Estado' => "Completada"],
    ['Identificador' => 003,
    'Descripcion' => "Estudiar inglés",
    'Estado' => "En proceso"]
];

// Devuelve la lista de tareas guardadas en el array $tareas
function devolverListadoTareas(){
    global $tareas;
    return $tareas;
}

// Filtra el campo que recibe por parámetros y lo devuelve filtrado
function filtrarCampo($campo){
    $campo = trim($campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo);
    return $campo;
}

// Comprueba que la información del campo cumple los requisitos establecidos
function informacionEsValida($campo){
    $campo = filtrarCampo($campo);
    // Comprobamos que el campo no esté vacío ni contenga caracteres especiales
    if (empty($campo) || !preg_match('/^[a-zA-Z0-9 ]*$/', $campo)) {
        return ['valido' => false, 'campo' => null];
    }else{
        return ['valido' => true, 'campo' => $campo];
    }
    // Devolvemos dos valores, porque si sólo devuelve true o false en función guardarTarea()
    // no tendremos las variables filtradas. Si es true devuelve también el campo filtrado
}

// Guarda la tarea en el array $tareas con los parámetros que se introducen
function guardarTarea($id, $descripcion, $estado){
    
    $resultadoId = informacionEsValida($id);
    $resultadoDescripcion = informacionEsValida($descripcion);
    $resultadoEstado = informacionEsValida($estado);
    
    // Estos 3 if comprueban que el campo válido que devuelve la función
    // anterior sea true. Si no lo es, imprime el mensaje y devuelve false
    if (!$resultadoId['valido']) {
        echo "El campo 'Identificador' no es válido.";
        return false;
    }
    if (!$resultadoDescripcion['valido']) {
        echo "El campo 'Descripción' no es válido.";
        return false;
    }
    if (!$resultadoEstado['valido']) {
        echo "El campo 'Estado' no es válido.";
        return false;
    }
    // Si campo válido es true crea nueva tarea y la introduce en el array
    $tareaNueva = [
        'Identificador' => $resultadoId['campo'], 
        'Descripción' => $resultadoDescripcion['campo'], 
        'Estado' => $resultadoEstado['campo']
    ];
    
    echo "La tarea se ha guardado correctamente.";
    global $tareas;
    array_push($tareas, $tareaNueva);
    return true;    
}

?>