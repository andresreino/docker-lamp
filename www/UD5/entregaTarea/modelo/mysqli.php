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

// Crea la tabla "tareas"    
function crearTablaFicheros(){
    $sql = "CREATE TABLE IF NOT EXISTS `tareas`.`ficheros` (
        `id` INT NOT NULL AUTO_INCREMENT , 
        `nombre` VARCHAR(100) NOT NULL , 
        `file` VARCHAR(250) NOT NULL , 
        `descripcion` VARCHAR(250) NOT NULL , 
        `id_tarea` INT NOT NULL , 
        PRIMARY KEY (`id`),
        FOREIGN KEY (id_tarea) REFERENCES tareas(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE)";

    return crearTabla("ficheros", $sql);
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
                    $usuario = new Usuario('', '', '', '', '');
                    $usuario->setId($fila['id']);
                    $usuario->setUsername($fila['username']);
                    $usuario->setNombre($fila['nombre']);
                    $usuario->setApellidos($fila['apellidos']);
                    $usuario->setRol($fila['rol']);
                    $usuario->setContrasena($fila['contrasena']);
                    $resultado[] = $usuario;
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

// Guarda la tarea en la DB con el objeto Tarea introducido por parámetro (Mysqli)
function guardarTarea($tarea){   
    try {
        $conexion = conectarDBmysqli('tareas');
        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else {
            
            $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
            $titulo = $tarea->getTitulo();
            $descripcion = $tarea->getDescripcion();
            $estado = $tarea->getEstado();
            $idUsuario = $tarea->getIdUsuario();
            
            $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $idUsuario);
            $stmt->execute();

            return [true, "Tarea guardada correctamente."];
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al guardar la tarea: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    }      
}

// Devuelve la lista de objetos Tarea guardados en la BD (Mysqli)
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
            $resultado = [];

            // En array introducimos cada fila con datos generada en un array con fetch_assoc() [En listarTareas hacemos con fetch_all(MYSQLI_ASSOC)]
            while($fila = $consulta->fetch_assoc()){
                $tarea = new Tarea('', '', '', 0);
                $tarea->setId($fila['id']);
                $tarea->setTitulo($fila['titulo']);
                $tarea->setDescripcion($fila['descripcion']);
                $tarea->setEstado($fila['estado']);
                $tarea->setIdUsuario($fila['id_usuario']);
                $resultado[] = $tarea;
            }

            // Devuelve un array de dos dimensiones con los objetos Tarea en $resultado. Si no hay: array(0)
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

// Edita una tarea introduciendo objeto Tarea por parámetro (Mysqli)
function editarTarea($tarea){
    try {
        $conexion = conectarDBmysqli('tareas');
        if ($conexion->connect_error) {
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else {
            
            $sql = "UPDATE tareas SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            
            $idTarea = $tarea->getId();
            $titulo = $tarea->getTitulo();
            $descripcion = $tarea->getDescripcion();
            $estado = $tarea->getEstado();
            $idUsuario = $tarea->getIdUsuario();

            //$sql = "UPDATE `tareas` SET `titulo`='$titulo',`descripcion`='$descripcion',`estado`='$estado',`id_usuario`='$idUsuario' WHERE id=" . $idTarea;
            $stmt->bind_param("sssii", $titulo, $descripcion, $estado, $idUsuario, $idTarea);
            $stmt->execute();
            if($stmt){
                return [true, "Tarea actualizada correctamente."];   
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al actualizar la tarea: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
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
            $fila = $stmt->fetch_assoc();

            $tarea = new Tarea('', '', '', 0);
            $tarea->setId($fila['id']);
            $tarea->setTitulo($fila['titulo']);
            $tarea->setDescripcion($fila['descripcion']);
            $tarea->setEstado($fila['estado']);
            $tarea->setIdUsuario($fila['id_usuario']);
            
            if($stmt){
                return [true, $tarea];
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