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

?>