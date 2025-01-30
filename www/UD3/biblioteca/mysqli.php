<?php
function conectarDBmysqli(){
    return new mysqli('db', 'root', 'test', 'biblioteca');
}

function cerrarConexionDBmysqli($conexion){
    if(isset($conexion)&& $conexion->connect_errno === 0) {
        $conexion->close();
    }
}

function mostrarLibros(){

    try {
        $conexion = conectarDBmysqli('tareas');

        if ($conexion->connect_error) {
            return "Error al conectar con la base de datos: " . $conexion->error;
        } else { 
        $sql = "SELECT `l`.*, `u`.`nombre`
        FROM `libros` AS `l` 
        LEFT JOIN `usuarios` AS `u` ON `l`.`id_usuario` = `u`.`id`";
  
        $stmt = $conexion->query($sql);
        $resultado = $stmt->fetch_all(MYSQLI_ASSOC);
            
        return $resultado;
        }
    } catch (mysqli_sql_exception $e) {
        return "Error al buscar los libros en la base de datos: " . $e->getMessage();
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

function mostrarLibrosNoDisponibles(){
    try {
        $conexion = conectarDBmysqli('tareas');
    
        if ($conexion->connect_error) {
            return "Error al conectar con la base de datos: " . $conexion->error;
        } else { 
        $sql = "SELECT * FROM libros WHERE disponible = false";
    
        $stmt = $conexion->query($sql);
        $resultado = $stmt->fetch_all(MYSQLI_ASSOC);
            
        return $resultado;
        }
    } catch (mysqli_sql_exception $e) {
        return "Error al buscar los libros en la base de datos: " . $e->getMessage();
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

function mostrarLibrosDisponibles(){
    try {
        $conexion = conectarDBmysqli('tareas');
    
        if ($conexion->connect_error) {
            return "Error al conectar con la base de datos: " . $conexion->error;
        } else { 
        $sql = "SELECT * FROM libros WHERE disponible = true";
    
        $stmt = $conexion->query($sql);
        $resultado = $stmt->fetch_all(MYSQLI_ASSOC);
            
        return $resultado;
        }
    } catch (mysqli_sql_exception $e) {
        return "Error al buscar los libros en la base de datos: " . $e->getMessage();
    } finally {
        cerrarConexionDBmysqli($conexion);
    } 
}

function registrarLibro($titulo){

    try {
        $conexion = conectarDBmysqli();

        if($conexion->connect_error){
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else {
            // Para que disponible e id_usuario sean true y NULL siempre ya ponemos ese valor en placeholder
            $stmt = $conexion->prepare("INSERT INTO libros (titulo, fecha_prestamo, disponible, id_usuario) VALUES (?, NULL, true, NULL)");

            $stmt->bind_param("s", $titulo);
            $stmt->execute();

            return [true, "Libro guardado correctamente."];
        }   
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al guardar libro: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    }    
}

function prestarLibro($id_usuario, $id_libro){

    try {
        $conexion = conectarDBmysqli();

        if($conexion->connect_error){
            return [false, "Error al conectar con la base de datos: " . $conexion->error];
        } else {
            // Para guardar la fecha actual, usamos función NOW() en placeholder
            // disponible lo cambiamos a "false" en el placeholder e id_usuario introducimos por parámetro con bind_param
            // Esta vez usamos $sql para pasar por parámetro a prepare(). Se puede hacer directamente (como función registrarLibro())
            $sql = "UPDATE libros SET fecha_prestamo = NOW(), disponible = false, id_usuario = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $id_usuario, $id_libro);
            $stmt->execute();

            return [true, "Solicitud de pŕestamo registrada correctamente."];
        }
    } catch (mysqli_sql_exception $e) {
        return [false, "Error al registrar la solicitud de préstamo: " . $e->getMessage()];
    } finally {
        cerrarConexionDBmysqli($conexion);
    }
}

?>