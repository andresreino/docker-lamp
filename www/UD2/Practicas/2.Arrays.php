<html>
    <head>
        <title>DWCS UD2. Boletín 2.</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        
        <h1>2. Arrays y estructuras de control</h1>

        <h3>Tarea 1. Uso de arrays</h3>

        <p>1.- Almacena en un array los 10 primeros números pares. Imprímelos cada uno en una línea.</p>
        <?php
        $numerosPares = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20];
        foreach ($numerosPares as $numero) { 
            echo $numero . "<br />";
        }
        /* Otra opción con bucle for
        for ($i = 0; $i < count($numerosPares); $i++)
            {
                echo $pares[$i] . '<br />';
            }
        */
        ?>

        <p>2.- Imprime los valores del array asociativo siguiente usando un foreach:</p>
        <?php
        $v[1]=90;
        $v[10] = 200;
        $v["hola"]=43;
        $v[9]="e";
        foreach ($v as $value) {
            echo $value . "<br />";
        }
        ?>

        <h3>Tarea 2. Arrays multidimensionales</h3>

        <p>Almacena la siguiente información en un array multidimensional e imprímela usando bucles.</p>

        <?php
        $datos = [
            [
                'name' => 'John',
                'email' => 'john@demo.com',
                'website' => 'www.john.com',
                'age' => 22,
                'password' => 'pass',

            ],
            [
                'name' => 'Anna',
                'email' => 'anna@demo.com',
                'website' => 'www.anna.com',
                'age' => 24,
                'password' => 'pass',

            ],
            [
                'name' => 'Peter',
                'email' => 'peter@mail.com',
                'website' => 'www.peter.com',
                'age' => 42,
                'password' => 'pass',

            ],
            [
                'name' => 'Max',
                'email' => 'max@mail.com',
                'website' => 'www.max.com',
                'age' => 33,
                'password' => 'pass',

            ]
        ];
        foreach ($datos as $index => $indice) { // Obtén el index (posición) y el valor contenido en cada uno
            $posicion = $index + 1; // Suma 1 al índice para obtener la posición
            echo "<strong>{$posicion} .- {$indice['name']}</strong>" . "<br />";
            foreach ($indice as $key => $value) {
                echo $key . ' = ' . $value . '<br />'; 
            }     
        } 
        ?>

        <h3>Tarea 3. Uso de Arrays</h3>
        
        <p>1.- Crea una matriz con 30 posiciones y que contenga números aleatorios entre 0 y 20 (inclusive). Uso de la función rand. Imprime la matriz creada anteriormente.</p>
        <?php
        $arrayAleatorios = array();
        for ($i = 0;$i < 30;$i++) { 
            array_push($arrayAleatorios, rand(0,20));
        }
        foreach ($arrayAleatorios as $value) {
            echo $value . ", ";
        }
        ?>

        <p>2.- Crea una matriz con los siguientes datos: Batman, Superman, Krusty, Bob, Mel y Barney</p>
        <ul>
            <li>Elimina la última posición de la matriz.</li>
            <li>Imprime la posición donde se encuentra la cadena 'Superman'.</li>
            <li>Agrega los siguientes elementos al final de la matriz: `Carl`, `Lenny`, `Burns` y `Lisa`.</li>
            <li>Ordena los elementos de la matriz e imprima la matriz ordenada.</li>
            <li>Agrega los siguientes elementos al comienzo de la matriz: `Apple`, `Melon`, `Watermelon`.</li>
            </ul>
        <?php
        $nombres = ["Batman", "Superman", "Krusty", "Bob", "Mel", "Barney"];
        array_pop($nombres); // Eliminamos último elemento
        $posicionSuperman = array_search("Superman", $nombres); // Buscamos posición en array y se introduce en variable
        echo "La posición de Superman en el array es la número " . $posicionSuperman . ".<br />";
        array_push($nombres, "Carl", "Lenny", "Burns", "Lisa");
        sort($nombres); // Ordena el array alfabéticamente
        echo "Array ordenado: <br />";
        foreach ($nombres as $value) {
            echo $value . ', ';
        }
        //print_r($nombres); Imprime todo el array indicando el índice
        // Array ( [0] => Batman [1] => Bob [2] => Burns...)
        echo '<br />';
        array_unshift($nombres, "Apple", "Melon", "Watermelon");
        echo "Array definitivo: <br />";
        print_r($nombres);
        ?>

        <p>3.- Crea una copia de la matriz con el nombre copia con elementos del 3 al 5.</p>
        <ul>
            <li>Agrega el elemento Pera al final de la matriz.</li>
        </ul>
        <?php
        $copia = array_slice($nombres, 2, 3); // Empieza en índice 2 (posición 3) y coge 3 índices desde allí (hasta posición 5)
        array_push($copia, "Pera");
        echo "Array copia: <br />";
        print_r($copia);
        ?>

        <h3>Tarea 4. Uso de arrays e Strings</h3>

        <p>En un string, tenemos almacenados varios datos agrupados en ciudad, país y continente. El formato es ciudad,pais,continente y cada grupo ciudad-pais-continente se separa con un ;.</p>

        <p>Crea una aplicación PHP que imprima toda la información almacenada en el string en una tabla con 3 columnas:</p>

        <?php
        $informacion = "Tokyo,Japan,Asia;Mexico City,Mexico,North America;New York City,USA,North America;Mumbai,India,Asia;Seoul,Korea,Asia;Shanghai,China,Asia;Lagos,Nigeria,Africa;Buenos Aires,Argentina,South America;Cairo,Egypt,Africa;London,UK,Europe";
        $filas = explode(';', $informacion); // explode devuelve un array de string. Parte el string original según el delimitador introducido y devuelve cada trozo como un índice del array (substring)
        ?>       

        <div class="table">
        <table class="table table-bordered mw-50 w-50" >
            <tr>
                <th>Ciudad</th>
                <th>País</th>
                <th>Continente</th>
            </tr>

            <?php          
            foreach ($filas as $value) {
                $dato = explode(',', $value);
                echo '<tr>';
                echo '<td>' . $dato[0] . '</td>';
                echo '<td>' . $dato[1] . '</td>';
                echo '<td>' . $dato[2] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        </div>
           
    </body>
</html>