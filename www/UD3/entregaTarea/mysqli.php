<?php
// Conecta con la base de datos cuyo nombre se introduce por parámetro mediante Mysqli
function conectarDBmysqli($nombreDB){
    return new mysqli('db', 'root', 'test', $nombreDB);
}

// Cierra la conexión establecida con la base de datos
function cerrarConexionDBmysqli($conexion){
    if(isset($conexion)&& $conexion->connect_errno === 0) {
        $conexion->close();
    }
}
?>