<?php 
include_once('usuarios/Usuario.php');

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
    if (empty($campo) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]*$/', $campo)) {
        return ['valido' => false, 'campo' => null];
    }else{
        return ['valido' => true, 'campo' => $campo];
    }
    // Devolvemos dos valores, porque si sólo devuelve true o false en función guardarTarea()
    // no tendremos las variables filtradas. Si es true devuelve también el campo filtrado
}

// Comprueba que todos los campos del usuario son correctos
function usuarioEsValido($nombre, $apellidos, $username, $contrasena) {
    // Asociamos cada variable introducida por parámetro a una clave para usar este array 
    // en la función genérica que valida la información
    $resultado = validarInformacion([
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'username' => $username,
        'contrasena' => $contrasena,
    ]);
    if(!$resultado[0]){
        return [false, $resultado[1]];   
    } else {
        return [true, "Usuario válido"];
    }
}

// Comprueba que todos los campos de la tarea son correctos
function tareaEsValida($titulo, $descripcion, $estado, $username){  
    $resultado = validarInformacion([
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'estado' => $estado,
        'username' => $username,
    ]);
    if (!$resultado[0]) {
        return [false, $resultado[1]];
    } else {
        return [true, "Tarea válida"];
    }
}

// AÑADIR INFO SI ERROR DEBAJO DE CADA CAMPO EN FORMULARIO EDITAR TAREA o NUEVA TAREA
// Sustituye a la función que hay justo debajo
// Función genérica que valida la información de los campos pasados en un array por parámetro
function validarInformacion(array $campos) {
    $errores = [];
    foreach ($campos as $nombre => $valor) {
        $resultado = informacionEsValida($valor);
        if (!$resultado['valido']) {
            $errores[$nombre] = "El campo '$nombre' no es válido.";
        }
    }
    if(!empty($errores)) {
        return [false, $errores];
    } else {
        return [true, "Información válida"];
    }
}

/*
// Función genérica que valida la información de los campos pasados en un array por parámetro
function validarInformacion(array $campos) {
    foreach ($campos as $nombre => $valor) {
        $resultado = informacionEsValida($valor);
        if (!$resultado['valido']) {
            return [false, "El campo '$nombre' no es válido."];
        }
    }
    return [true, "Información válida"];
}*/

// Muestra el resultado del acceso a la DB
function mostrarResultado($resultado){
    // Si resultado[0] es true
    if($resultado[0]){
        // En caso de resultado[1] sea un array, imprime todo su contenido
        if(is_array($resultado[1])){
            foreach ($resultado[1] as $result) {       
                echo '<div class="alert alert-success" role="alert">' . $result . '</div>';   
            }
        } else {
            // En caso de resultado[1] sea un String lo imprime directamente
            echo '<div class="alert alert-success" role="alert">' . $resultado[1] . '</div>';  
        }
    } else {
        if(is_array($resultado[1])){
            foreach ($resultado[1] as $result) {
                echo '<div class="alert alert-warning" role="alert">' . $result. '</div>';   
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">' . $resultado[1] . '</div>';   
        }
    }
}

// Muestra los usuarios disponibles en un formulario recibiendo un array de objetos Usuario
function mostrarUsuariosForm($resultado){
    if ($resultado[0] && count($resultado[1]) > 0) {
        // Recorremos los objetos Usuario en resultado[1] y guardamos username en $data
        foreach ($resultado[1] as $usuario) {
            $data = $usuario->getUsername();
            echo "<option value=$data>$data</option>";
        }   
    } else {
        echo '<option value="" disabled >No existen usuarios</option>';
    }
}

/*Muestra los usuarios disponibles en un formulario indicando el 
usuario que estaba cargado en el campo (usado para EDITAR TAREA)*/
function mostrarUsuarioSeleccionadoForm($resultado, $username){
    if ($resultado[0] && count($resultado[1]) > 0) {
        foreach ($resultado[1] as $usuario) {
            $data = $usuario->getUsername();
            if($username === $data){
                // Para que muestre usuario seleccionado al cargar formulario
                echo "<option value=$data selected>$data</option>";
            }else{
                echo "<option value=$data >$data</option>";
            }
        }   
    }
}

// Muestra las opciones disponibles en un formulario para el estado de la tarea
function mostrarOpcionesEstadoTareaForm(){
    echo '<option value="" disabled selected>Seleccione un estado</option>';
    echo '<option value="pendiente">Pendiente</option>';
    echo '<option value="enproceso">En proceso</option>';
    echo '<option value="completada">Completada</option>';
}

// Muestra la cabecera de la tabla de tareas
function mostrarCabeceraTablaTareas(){
    echo '<thead class="thead">';
    echo '<tr>';                            
    echo '<th>ID</th>';
    echo '<th>Título</th>';
    echo '<th>Descripción</th>';
    echo '<th>Estado</th>';
    echo '<th>Username</th>';
    echo '<th></th>'; // Header para los botones 
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';  
}

// Muestra el listado de objetos Tarea introducido por parámetro
function mostrarListadoTareas($lista){
    foreach ($lista[1] as $tarea) { 
        $idTarea = $tarea->getId();
        $idUsuario = $tarea->getIdUsuario();
        $usuario = buscarUsuario($idUsuario); 
        $username = $usuario[1]->getUsername();                                     
        echo '<tr>';
        echo '<td>' . $idTarea . '</td>';   
        echo '<td>' . $tarea->getTitulo() . '</td>';   
        echo '<td>' . $tarea->getDescripcion() . '</td>';   
        echo '<td>' . $tarea->getEstado() . '</td>';
        echo '<td>' . $username . '</td>';
        echo '<td>';
        // Usamos <a> para incluir los botones y que nos lleven a cada php correspondiente (incluir role="button")
        // Incluimos el id de la tarea en la url al hacer click en el botón
        echo '<a class="btn btn-info" href="tarea.php?id=' . $idTarea . '" role="button">Mostrar</a> '; 
        echo '<a class="btn btn-success" href="editaTareaForm.php?id=' . $idTarea . '" role="button">Editar</a> '; 
        echo '<a class="btn btn-danger" href="borraTarea.php?id=' . $idTarea . '" role="button">Borrar</a>';   
        echo '</td>';
        echo '</tr>';        
    }
    echo '</tbody>';
}

// Muestra la información guardada en variables de sesión superglobal (success y messages)
function mostrarMensajeSESSION(){
    $success = $_SESSION["usuario"]["success"]; // Guarda 0 (false) o 1 (true)
    $messages = $_SESSION["usuario"]["messages"];
    $resultado = [$success, $messages];
    mostrarResultado($resultado);
    
    // Destruimos las variables de sesión para que no se vuelvan a mostrar si recargamos la página
    unset($_SESSION["usuario"]["success"]);
    unset($_SESSION["usuario"]["messages"]);
}

// Muestra la información guardada en variables de sesión superglobal (success y messages)
function mostrarMensajeSESSIONVariableUnica($variable){
    $success = $_SESSION["usuario"]["success"]; // Guarda 0 (false) o 1 (true)
    $resultado = [$success, $variable];
    mostrarResultado($resultado);
    
    // Destruimos la variable de sesión particular para que no se vuelva a mostrar si recargamos la página
    unset($variable);
}

// Muestra la información guardada en variable de sesión superglobal (errors)
function mostrarMensajeSessionErrors(){
        $errors = $_SESSION["usuario"]["errors"];
        foreach($errors as $error){
            echo '<div>';
            echo '<div class="alert alert-warning" role="alert">' . $error;
            echo '</div>';
        }
        // Eliminamos la variable superglobal para que no siga mostrando errores si recargamos página
        unset($_SESSION["usuario"]["errors"]);
}

// Limpia referer en web que devuelven algo por GET (success, message...) por si lleva algún mensaje guardado de anteriores visitas a la web solicitada
function limpiarReferer($referer){
    // Buscamos si existe ? en la cadena. Si existe devuelve posición, si no False
    $posicionInterrogante = strpos($referer, '?');
    // Si existe, con substr nos quedamos con lo que haya en $referer desde el principio hasta la posición de ?
    // Si no existe devuelve $_SERVER['HTTP_REFERER'] (es lo que pasamos por parámetro a la función)
    return $posicionInterrogante !== false ? substr($referer, 0, $posicionInterrogante) : $referer;
}

?>