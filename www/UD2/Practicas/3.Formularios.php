<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DWCS UD2. Boletín 4.</title>
</head>
<body>
    <h1>3. Formularios</h1>

    <h3>Tarea 1. Formularios y Strings</h3>

    <p>Crea un formulario que solicite nombre y apellido.</p>

    <p>Cuando se reciben los datos, se debe mostrar la siguiente información:</p>

    <ul>
        <li>Nombre: xxxxxxxxx</li>
        <li>Apellidos: xxxxxxxxx</li>
        <li>Nombre y apellidos: xxxxxxxxxxxx xxxxxxxxxxxx</li>
        <li>Su nombre tiene caracteres X</li>
        <li>Los 3 primeros caracteres de tu nombre son: xxx</li>
        <li>La letra A fue encontrada en sus apellidos en la posición: X</li>
        <li>Su nombre contiene X caracteres “A”.</li>
        <li>Tu nombre en mayúsculas es: XXXXXXXXX</li>
        <li>Sus apellidos en minúsculas son: xxxxxx</li>
        <li>Su nombre y apellido en mayúsculas: XXXXXX XXXXXXXXXX</li>
        <li>Tu nombre escrito al revés es: xxxxxx</li>
    </ul>

    <form action="3.FormulariosNombre.php" method="POST" >
        Nombre: <input type="text" id="nombre" name="Nombre">
        <br><br>
        Apellidos: <input type="text" id="apellidos" name="Apellidos">
        <br><br>
        <input type="submit" value="Enviar">
    </form>
    
    <h3>Tarea 2. Envío de formularios</h3>

    <p>Crea un formulario para solicitar una de las siguientes bebidas:</p>
        
    <form action="3.FormulariosBebida.php" method="POST" >
        <label for="bebida">Selecciona una bebida:</label>
        <select id="bebida" name="Bebida" required >
            <option value="Coca Cola">Coca Cola - 1 €</option>
            <option value="Pepsi Cola">Pepsi Cola - 0,80 €</option>
            <option value="Fanta Naranja">Fanta Naranja - 0,90 €</option>
            <option value="Trina Manzana">Trina Manzana - 1,10 €</option>
        </select>
        <br><br>
        <label for="cantidad">Cantidad de bebidas:</label>
        <input type="number" id="cantidad" name="Cantidad" min="1" required>
        <br><br>
        <input type="submit" value="Solicitar">
    </form>
    
    
   

    <?php



    ?>




</body>
</html>