<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DocDWCS UD2. Boletín 3.</title>
</head>
<body>
<h1>Anexo 3. Formularios</h1>
    
    <h3>Tarea 1. Uso de arrays</h3>
    
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $nombre = $_POST["Nombre"];
            $apellidos = $_POST["Apellidos"];

            $numeroCaracteres = strlen($nombre);
            $tresPrimerosCaracteres = substr($nombre, 0, 3);
            $letraPosicion = strpos(strtolower($apellidos), "a");
            $letraRepetida = substr_count(strtolower($nombre), "a");

            
            echo "<p>Nombre: $nombre </p>";
            echo "<p>Apellidos: $apellidos </p>";
            echo "<p>Nombre y apellidos: $nombre $apellidos </p>";
            echo "<p>Su nombre tiene caracteres: $numeroCaracteres </p>";
            echo "<p>Los 3 primeros caracteres de tu nombre son: $tresPrimerosCaracteres </p>";
            echo "<p>La letra A fue encontrada en sus apellidos en la posición: $letraPosicion </p>";
            echo "<p>Su nombre contiene $letraRepetida caracteres A.</p>";
            echo "<p>Tu nombre en mayúsculas es: " . strtoupper($nombre) . "</p>";
            echo "<p>Sus apellidos en minúsculas son: " . strtolower($apellidos) . "</p>";
            echo "<p>Su  nombre y  apellidos en minúsculas son: " . strtoupper($nombre) . " " . strtoupper($apellidos) . "</p>";
            echo "<p>Tu nombre escrito al revés: " . strrev($nombre) . "</p>";
        }
    ?>

    <a href="3.Formularios.php" class="btn btn-info">Volver</a>

</body>
</html>