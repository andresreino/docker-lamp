<?php
function conectarDBPDO(){
    $servername = 'db';
    $username = 'root';
    $password = 'test';
    $dbname = 'biblioteca';
    
    $conPDO = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conPDO;
}

function mostrarUsuarios(){

    try {
        $conexion = conectarDBPDO();
        $sql = "SELECT * FROM `usuarios`";

        $stmt = $conexion->query($sql);
        // Así también sirve
        //$stmt->setFetchMode(PDO::FETCH_ASSOC);
        //$resultado = $stmt->fetchAll();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    } catch (PDOException $e) {
        return "Ha ocurrido un error mostrando usuarios: " . $e->getMessage();
    }finally{
        $conexion = null;
    }
}

function guardarUsuario($nombre, $apellidos, $localidad){
    try {
        $conexion = conectarDBPDO();

        $sql = "INSERT INTO `usuarios`(`nombre`, `apellidos`, `localidad`) VALUES (:nombre,:apellidos,:localidad)";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':localidad', $localidad);

        $stmt->execute();
        $stmt->closeCursor();

        return [true, "Usuario guardado correctamente"];
        
    } catch (PDOException $e) {
        return [false, "Error al guardar usuario: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}







?>