<?php

// Conecta con la base de datos cuyo nombre se introduce por parámetro mediante Mysqli
function conectarDBmysqli($nombreDB){
    // Creamos la conexión utilizando las variables de entorno del archivo .env
    $servername = $_ENV['DATABASE_HOST'];
    $username = $_ENV['DATABASE_USER'];
    $password = $_ENV['DATABASE_PASSWORD'];

    return new mysqli($servername, $username, $password, $nombreDB);
}

// Cierra la conexión establecida con la base de datos
function cerrarConexionDBmysqli($conexion){
    if(isset($conexion)&& $conexion->connect_errno === 0) {
        $conexion->close();
    }
}

// Crea la base de datos con el  nombre introducido por parámetro mediante Mysqli
function crearDB($nombreDB){
    try {
        // Probamos conexión con DB introduciendo nombre como null 
        $conexion = conectarDBmysqli(null);
        if ($conexion->connect_error){
            return [false, $conexion->error];
        }else{
            // Verificar si la base de datos ya existe
            $sql = "SHOW DATABASES LIKE '$nombreDB'";
            $resultado = $conexion->query($sql);
            
            if ($resultado->num_rows > 0) {
                return [false, "La base de datos '$nombreDB' ya existe."];
            }else{
                // Creamos la BD si no existe. OJO a estas comillas dentro de la consulta, no son las simples ''
                $sql = "CREATE DATABASE IF NOT EXISTS `$nombreDB`";
                // También sería válida la siguiente forma:
                //$sql = "CREATE DATABASE IF NOT EXISTS " . $nombreDB;
                
                if($conexion->query($sql)){
                    return [true, "Base de datos '$nombreDB' creada correctamente."];
                }else{
                    return [false, "Ha ocurrido un error creando la base de datos: " + $conexion->error];
                }
            }
        }    
    } catch (mysqli_sql_exception $e){
        return [false, $e->getMessage()];
    } finally{
        cerrarConexionDBmysqli($conexion);
    }
}

// Función genérica para crear una tabla (Mysqli)
function crearTabla($nombreTabla, $sqlScript) {
    try {
        $conexion = conectarDBmysqli('tareas');
        
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        } else {           
            // Verificar existencia de la tabla
            $sql = "SHOW TABLES LIKE '$nombreTabla'";
            $resultado = $conexion->query($sql);
            
            // Si no existe, num_rows=0
            if ($resultado && $resultado->num_rows > 0) {
                return [false, "La tabla '$nombreTabla' ya existe."];
            } else {
                // Script sql lo introducimos por parámetro
                $resultado = $conexion->query($sqlScript);
                if ($conexion->query($sqlScript)) {
                    return [true, "Tabla '$nombreTabla' creada correctamente."];
                } else {
                    return [false, "No se pudo crear la tabla '$nombreTabla'."];
                }   
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    }  
}

// Crea la tabla "usuarios"  
function crearTablaUsuarios(){
    $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`usuarios` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `username` VARCHAR(50) NOT NULL , 
        `nombre` VARCHAR(50) NOT NULL , 
        `apellidos` VARCHAR(100) NOT NULL , 
        `rol`INT NOT NULL DEFAULT 0,
        `contrasena` VARCHAR(100) NOT NULL , 
        PRIMARY KEY (`id`))";
    
    return crearTabla("usuarios", $sql);
}

// Crea la tabla "tareas"    
function crearTablaTareas(){
    $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`tareas` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `titulo` VARCHAR(50) NOT NULL , 
    `descripcion` VARCHAR(250) NOT NULL , 
    `estado` VARCHAR(50) NOT NULL , 
    `id_usuario` INT NOT NULL , 
    PRIMARY KEY (`id`),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE)";

    return crearTabla("tareas", $sql);
}

// Devuelve la lista de usuarios existentes en la DB (Mysqli)
function listarUsuariosMysqli(){
    try {
        $conexion = conectarDBmysqli('tareas');

        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else {            
            $sql = "SELECT * FROM usuarios";
            $consulta = $conexion->query($sql);
            if ($consulta && $consulta->num_rows > 0) {
                $resultado = [];
                // En array introducimos cada fila con datos generada en un array con fetch_assoc() [En listarTareas hacemos con fetch_all(MYSQLI_ASSOC)]
                while($fila = $consulta->fetch_assoc()){
                    $resultado[] = $fila;
                }
                return [true, $resultado];
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al listar los usuarios de la base de datos: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    }  
}

// Guarda la tarea en la DB con los parámetros que se introducen (Mysqli)
function guardarTarea($titulo, $descripcion, $estado, $username){
    // En esta variable guardamos array con booleano true/false[0] + mensaje[1]
    $tareaValida = tareaEsValida($titulo, $descripcion, $estado, $username);
    
    if($tareaValida[0]){
        try {
            $conexion = conectarDBmysqli('tareas');
            if ($conexion->connect_error) {
                return [false, "Error al conectar con la base de datos: " . $conexion->error];
            } else {
                // OJO: El valor de username tiene que ir entre comillas (es String)  
                $sql = "SELECT id FROM usuarios WHERE username = '" . $username . "'";

                $idUsuario = $conexion->query($sql);
                // Guardamos array asociativo: [id]=>"id de usuario"
                $idUsuario = $idUsuario->fetch_assoc();
                
                $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
              
                $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $idUsuario["id"]);
                $stmt->execute();

                return [true, "Tarea guardada correctamente."];
            }
        } catch (mysqli_sql_exception $e) {
            return [false, "Error al guardar la tarea: " . $e->getMessage()];
        } finally {
            cerrarConexionDBmysqli($conexion);
        }  
    }else{
        // Si la tarea no valida todos los campo devuelve array con false[0] e info del error[1]
        return $tareaValida;
    }
}

// Devuelve la lista de tareas guardadas (Mysqli)
function listarTareas(){
    try {
        // Inicializamos variable $conexion por si no existe la DB y evitar warning al cerrar conexión al no estar definida
        $conexion = null;
        $conexion = conectarDBmysqli('tareas');

        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else { 
            // Seleccionamos todas columnas de tareas y username de usuarios 
            $sql = "SELECT t.*, u.username
            FROM tareas t JOIN usuarios u
            ON t.id_usuario = u.id";          
            
            $consulta = $conexion->query($sql);
            $resultado = $consulta->fetch_all(MYSQLI_ASSOC);
            // Devuelve un array de dos dimensiones con las tareas. Si no hay: array(0)
            return [true, $resultado];
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1049) { // Código 1049: base de datos no encontrada
            return [false, "No existe la base de datos."];
        } else {
            return [false, "Error al listar los usuarios de la base de datos: " . $e->getMessage()];
        }
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

// Edita los datos de una tarea (Mysqli)
function editarTarea($idTarea, $titulo, $descripcion, $estado, $username){
    
    $tareaValida = tareaEsValida($titulo, $descripcion, $estado, $username);
    
    if($tareaValida[0]){
        try {
            $conexion = conectarDBmysqli('tareas');
            if ($conexion->connect_error) {
                return [false, "Error al conectar con la base de datos: " . $conexion->error];
            } else {
                 
                $sqlId = "SELECT id FROM usuarios WHERE username='" . $username . "'";

                $stmt = $conexion->query($sqlId);
                $resultado = $stmt->fetch_assoc();
                // Guardamos en esta variable valor de id por complejidad de comillas en consulta posterior
                $idUsuario = $resultado["id"];

                $sql = "UPDATE `tareas` SET `titulo`='$titulo',`descripcion`='$descripcion',`estado`='$estado',`id_usuario`='$idUsuario' WHERE id=" . $idTarea;

                if($conexion->query($sql)){
                    return [true, "Tarea actualizada correctamente."];
                }
            }
        } catch (mysqli_sql_exception $e) {
            return [false, "Error al actualizar la tarea: " . $e->getMessage()];
        } finally {
            cerrarConexionDBmysqli($conexion);
        }  
    }else{
        // Si la tarea no valida todos los campo devuelve array con false[0] e info del error[1]
        return $tareaValida;
    }     
}

// Busca la tarea con id introducido por parámetro (Mysqli)
function buscarTarea($id){
    try {
        $conexion = conectarDBmysqli('tareas');

        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else { 
            $sql = "SELECT t.*, u.username 
            FROM tareas t JOIN usuarios u 
            ON t.id_usuario = u.id
            WHERE t.id =" . $id;

            
            $stmt = $conexion->query($sql);
            $resultado = $stmt->fetch_assoc();
            
            if($stmt){
                return [true, $resultado];
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al buscar la tarea en la base de datos: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

// Borra la tarea con id_usuario introducido por parámetro de la DB (Mysqli)
function borrarTarea($id){
    try {
        $conexion = conectarDBmysqli('tareas');

        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else { 
            $sql = "DELETE FROM tareas WHERE id = " . $id;
            
            if ($conexion->query($sql)) {
                return [true, "Tarea eliminada correctamente."];
            } else {
                return [true, "La tarea no ha podido ser eliminada."];
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al eliminar la tarea de la base de datos: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

?>