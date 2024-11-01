<?php
/*
include:

    Incluye y evalúa un archivo especificado.
    Si el archivo no se encuentra o no se puede incluir, PHP genera una advertencia (warning) pero continúa la ejecución del script.
    Se utiliza cuando el archivo no es estrictamente necesario para que el script continúe.

include_once:

    Funciona igual que include, pero asegura que el archivo solo sea incluido una vez, incluso si la instrucción se ejecuta varias veces.
    Evita la inclusión repetida de un archivo, lo que podría provocar errores (por ejemplo, redefiniciones de funciones o clases).


require:

    Similar a include, pero si el archivo no se encuentra o no se puede incluir, genera un error fatal (fatal error) y detiene la ejecución del script.
    Se utiliza cuando el archivo es crucial para que el script funcione correctamente.

require_once:

    Igual que require, pero asegura que el archivo solo sea incluido una vez.
    Es útil para evitar errores relacionados con la inclusión múltiple del mismo archivo.
*/

function esDigito($caracter){
    if(ctype_digit($caracter) && $caracter >= 0 && $caracter <= 9){
        echo "El caracter $caracter es un dígito entre 0 y 9.<br />";
    }else{
        echo "El caracter $caracter no es un dígito entre 0 y 9.<br />";
    }
}

function longitudCadena($cadena){
    return strlen($cadena);
}

function elevarNumero($a, $b){
    return pow($a, $b); // 2 indica el número de decimales, de ser necesarios
}

function esVocal($caracter){
    $vocales = "aeiouAEIOUáéíóúÁÉÍÓÚ";
    if(str_contains($vocales, $caracter)){
        echo "El caracter $caracter es una vocal.<br />";
        return true;
    }else{
        echo "El caracter $caracter no es una vocal.<br />";
        return false;
    }
}

function parImpar($numero){
    return $numero % 2 == 0 ? "$numero es par" : "$numero es impar";
}

function convertirMayusculas($texto){
    return strtoupper($texto);
}

function imprimirZonaHoraria(){
    echo "La zona horaria por defecto es: " . date_default_timezone_get();
}

function imprimirSalidaPuestaSol($latitud = 42.8782, $longitud = -8.5448) {
    $info = date_sun_info(time(), $latitud, $longitud);
    /*foreach ($info as $key => $val)
    {
        echo "$key: " . date("H:i:s", $val) . '<br>';
    }*/

    echo 'Hora de salida del sol: ' . date("H:i:s", $info['sunrise']) . '<br>';
    echo 'Hora de puesta del sol: ' . date("H:i:s", $info['sunset']) . '<br>';
}

?>