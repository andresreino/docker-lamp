<?php

require_once 'flight/Flight.php';

// Utilizamos variables de entorno (archivo .env) en lugar de poner directamente los datos de acceso al hacer la conexión
$dbHost = $_ENV['DATABASE_HOST'];
// En .env añadimos variable DATABASE_TEST con nombre de BD de esta unidad para mantener DATABASE_NAME (de ejercicios unidades anteriores)
$dbName = $_ENV['DATABASE_TEST'];
$user = $_ENV['DATABASE_USER'];
$password = $_ENV['DATABASE_PASSWORD'];

// Conexión a la BD "db" a través de PDO y parámetros necesarios
Flight::register('db', 'PDO', array("mysql:host=$dbHost;dbname=$dbName", $user, $password));

Flight::route('/', function () {
    echo 'API HOTELES';
});

// Ruta puede tener id en ella. Por si no lo trae, en la función se inicializa a null
Flight::route('GET /clientes(/@id)', function ($id = null) {
    if($id){
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = Flight::db()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // Usamos fetch porque sólo habrá un objeto como respuesta
        $datos = $stmt->fetch();

    } else {
        $sql = "SELECT * FROM clientes";
        $stmt = Flight::db()->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll();
    }
    // Devolvemos los datos obtenidos en formato json
    Flight::json($datos);
        
});

Flight::route('POST /clientes', function () {
    $nombre = Flight::request()->data->nombre;
    $apellidos = Flight::request()->data->apellidos;
    $edad = Flight::request()->data->edad;
    $email = Flight::request()->data->email;
    $telefono = Flight::request()->data->telefono;  

    // Asociamos parámetros "?" de la consulta por posición: 1 con primer ?, 2 con segundo ?...
    $sql = "INSERT INTO clientes(nombre, apellidos, edad, email, telefono) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1,$nombre);
    $stmt->bindParam(2,$apellidos);
    $stmt->bindParam(3,$edad);
    $stmt->bindParam(4,$email);
    $stmt->bindParam(5,$telefono);
    $stmt->execute();

    // Como no devuelve nada y por respetar el json, hacemos una devolución de este mensaje
    // OJO, usa jsonp (json with Padding)
    Flight::jsonp("Usuario guardado correctamente.");
});

Flight::route('DELETE /clientes', function () {
    $id = Flight::request()->data->id;

    // Llamamos a función para verificar que cliente existe en BD
    $clienteExiste = verificarCliente($id);
    // Si cliente existe guarda en array todos sus datos; si no, devuelve false

    if(!$clienteExiste){
        Flight::jsonp("Usuario $id no existe en la base de datos.");
        Flight::stop();
        exit;
    }
 
    $sql = "DELETE FROM clientes WHERE id = :id";
    
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();

    // No devuelve nada, hacemos igual que en POST
    Flight::jsonp("Usuario $id borrado correctamente.");
});

Flight::route('PUT /clientes', function () {
    // Necesitamos id para actualizar sólo este usuario y todos sus datos
    $id = Flight::request()->data->id;
    $nombre = Flight::request()->data->nombre;
    $apellidos = Flight::request()->data->apellidos;
    $edad = Flight::request()->data->edad;
    $email = Flight::request()->data->email;
    $telefono = Flight::request()->data->telefono;  

    $clienteExiste = verificarCliente($id);
    // Si cliente existe guarda en array todos sus datos; si no, devuelve false

    if(!$clienteExiste){
        Flight::jsonp("Usuario $id no existe en la base de datos.");
        Flight::stop();
        exit;
    }

    $sql = "UPDATE clientes SET nombre=?, apellidos=?, edad=?, email=?, telefono=? WHERE id = ?";
    
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1,$nombre);
    $stmt->bindParam(2,$apellidos);
    $stmt->bindParam(3,$edad);
    $stmt->bindParam(4,$email);
    $stmt->bindParam(5,$telefono);
    $stmt->bindParam(6,$id);
    $stmt->execute();

    // No devuelve nada, hacemos igual que en POST
    Flight::jsonp("Usuario $id actualizado correctamente.");
});

// Verifica que un cliente existe en la BD
function verificarCliente($id){
    $sqlCliente = "SELECT * FROM clientes WHERE id = :id";
    $stmt = Flight::db()->prepare($sqlCliente);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $clienteExiste = $stmt->fetch();

    return $clienteExiste;
}

Flight::start();

