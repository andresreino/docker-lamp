<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DWCS UD2. Boletín 4.</title>
</head>
<body>
    <h1>4. Funciones</h1>

    <h3>Tarea 1. Uso de funciones</h3>

    <ol>
        <li>Crea una función que reciba un carácter e imprima si el carácter es un dígito entre 0 y 9.</li>
        <?php
        function esDigito($caracter){

            if(ctype_digit($caracter) && $caracter >= 0 && $caracter <= 9){
                echo "El caracter $caracter es un dígito entre 0 y 9.<br />";
            }else{
                echo "El caracter $caracter no es un dígito entre 0 y 9.<br />";
            }
        }
        // Para que funcione ctype_digit hay que meter un string
        esDigito("8");
        ?>

        <li>Crea una función que reciba un string y devuelva su longitud.</li>
        <?php
        function longitudCadena($cadena){
            return strlen($cadena);
        }
        echo "La longitud de la cadena 'Mi casa es roja' es: " . longitudCadena("Mi casa es roja");
        ?>

        <li>Crea una función que reciba dos números a y b y devuelva el número a elevado a b.</li>
        <?php
        function elevarNumero($a, $b){
            return pow($a, $b); // 2 indica el número de decimales, de ser necesarios
        }
        echo "5 elevado a 3 es igual a " . elevarNumero('5','3');
        ?>

        <li>Crea una función que reciba un carácter y devuelva true si el carácter es una vocal.</li>
        <?php
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
        esVocal("b");
        ?>

        <li>Crea una función que reciba un número y devuelva si el número es par o impar.</li>
        <?php
        function parImpar($numero){
            return $numero % 2 == 0 ? "$numero es par" : "$numero es impar";
        }
        echo parImpar(5) . "<br />";
        echo parImpar(8);
        ?>

        <li>Crea una función que reciba un string y devuelva el string en maiúsculas.</li>
        <?php
        function convertirMayusculas($texto){
            return strtoupper($texto);
        }
        echo convertirMayusculas("La casa de Fernando es roja.");
        ?>

        <li>Crea una función que imprima la zona horaria (timezone) por defecto utilizada en PHP.</li>
        <?php
        function imprimirZonaHoraria(){
            echo "La zona horaria por defecto es: " . date_default_timezone_get();
        }
        imprimirZonaHoraria();
        ?>

        <li>Crea una función que imprima la hora a la que sale y se pone el sol para la localicación por defecto. Debes comprobar como ajustar las coordenadas (latitud y longitud) predeterminadas de tu servidor.</li>

    </ol>

    <h3>Tarea 2. Programa DNI</h3>

    <p>Crea una función llamada comprobar_nif() que reciba un NIF y devuelva:</p>

    <ul>
        <li><strong>true </strong>si el NIF es correcto.</li>
        <li><strong>false</strong> si el NIF no es correcto.</li>
    </ul>

    <p>La letra del DNI es una letra para comprobar que el número introducido anteriormente es correcto. Para obtner la letra del DNI, se deben llevar a cabo los siguientes pasos:</p>

    <ul>
        <li>Dividimos el número entre 23.</li>
        <li>Con el resto de la división anterior, obtenemos la posición en la siguiente tabla para recuperar la letra:</li>
    </ul>

    <?php
    function comprobar_nif($nif){
        if(strlen($nif) != 9){
            return false;
        }

        $numeros = substr($nif, 0, 8);
        $letraDNI = strtoupper(substr($nif, -1)); // Devuelve la última posición del string

        $restoDivision = $numeros % 23;
        $letrasDNILista = "TRWAGMYFPDXBNJZSQVHLCKE";

        $posicionLetra = strpos($letrasDNILista, $letraDNI);

        if($restoDivision == $posicionLetra){
            return true;
        }else{
            return false;
        }
        //return $restoDivision === $posicionLetra; // Tb podemos poner así el return directamente. Si ambas variables son iguales y del mismo tipo devuelve true.

    }
    $nif = "12345678Z";
        if (comprobar_nif($nif)) {
            echo "El NIF $nif es correcto. <br>";
        } else {
            echo "El NIF $nif no es correcto. <br>";
        }

    ?>

</body>
</html>