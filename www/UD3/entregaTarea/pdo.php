<?php
// Conecta con la base de datos cuyo nombre se introduce por parámetro mediante PDO
function conectarDBPDO($nombreDB){
    $servername = 'db';
    $username = 'root';
    $password = 'test';
    $dbname = $nombreDB;

    $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Forzar excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conexion;
}
?>
