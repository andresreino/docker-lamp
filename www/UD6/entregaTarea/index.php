<?php

require_once 'flight/Flight.php';
require_once ('utils.php');

// Utilizamos variables de entorno (archivo .env) en lugar de poner directamente los datos de acceso al hacer la conexión
$dbHost = $_ENV['DATABASE_HOST'];
// En .env añadimos variable DATABASE_UD6 con nombre de BD de esta unidad 
$dbName = $_ENV['DATABASE_UD6'];
$user = $_ENV['DATABASE_USER'];
$password = $_ENV['DATABASE_PASSWORD'];

// Conexión a la BD "db" a través de PDO y parámetros necesarios
Flight::register('db', 'PDO', array("mysql:host=$dbHost;dbname=$dbName", $user, $password));

Flight::route('POST /register', function () {
    $nombre = Flight::request()->data->nombre;
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;

    if(!nombreEsValido($nombre) || !emailEsValido($email) || !passwordEsValido($password)){
        // Devolvemos error 400 (Algún dato mal introducido)
        Flight::json(['error' => "Error al guardar el usuario."], 400);
        Flight::stop();
        // Detenemos la ejecución de la función y salimos
        exit;
    }    
        
    // Creamos un hash a partir de la contraseña que se recibe del request
    $hasheado = password_hash($password, PASSWORD_DEFAULT);
    
    // Asociamos parámetros "?" de la consulta por posición: 1 con primer ?, 2 con segundo ?...
    $sql = "INSERT INTO usuarios(nombre, email, password) VALUES (?, ?, ?)";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1,$nombre);
    $stmt->bindParam(2,$email);
    $stmt->bindParam(3,$hasheado);
    $stmt->execute();
    
    Flight::json("El usuario $nombre se ha guardado correctamente.");
});

Flight::route('POST /login', function(){
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;
    $usuario = seleccionarUsuarioPorEmail($email);

    // Comprobamos que usuario existe y contraseña es válida (verificamos el hash que hay guardado en BD)
    if(!$usuario || !password_verify($password, $usuario['password'])){
        // Devolvemos error 401 (Fallo en la autorización)
        Flight::json(['error' => 'Error en el login del usuario'], 401);
        Flight::stop();
        exit;
    }
    
    // Si valida lo anterior, generamos un token
    $token = bin2hex(random_bytes(32));
    $idUsuario = $usuario['id'];
    // Actualizamos BD con valor del token
    $sql = "UPDATE usuarios SET token=:token WHERE id = :id";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':id', $idUsuario);
    $stmt->execute();
    // Devolvemos mensaje y valor del token
    Flight::json(['success' => 'Usuario logueado correctamente', 'token' => $token]);
});

// Este servicio puede recibir el id de un contacto concreto para mostrar ese únicamente o listar todos los del usuario
Flight::route('GET /contactos(/@id)', function($idContacto = null){
    // Recogemos el token que se envía dentro del header de la request de Thunder
    $token = Flight::request()->getHeader('X-Token');
    $usuario = seleccionarUsuarioPorToken($token);
    $idUsuario = $usuario['id'];

    // Si no existe token o no existe usuario
    if(!$token || !$usuario){
        // Devolvemos error 401 (Fallo en la autenticación)
        Flight::json(['error' => 'Error en la autenticación del usuario'], 401);
        Flight::stop();
        exit;
    }
    
    if($idContacto){
        // Verificamos que contacto existe en BD
        $contactoExiste = verificarContacto($idContacto);
    
        if(!$contactoExiste){
            // Devolvemos error 404 (Contacto no existe)
            Flight::json(['error' => 'Error, el contacto no existe en la base de datos'], 404);
            Flight::stop();
            exit;
        }

        $sql = "SELECT * FROM contactos WHERE usuario_id = :usuario_id AND id = :contacto_id";
        $stmt = Flight::db()->prepare($sql);
        $stmt->bindParam(':usuario_id', $idUsuario);
        $stmt->bindParam(':contacto_id', $idContacto);
        $stmt->execute();
        // fetch(), ya que sólo devuelve un contacto
        $datos = $stmt->fetch();
    } else {
        $sql = "SELECT * FROM contactos WHERE usuario_id = :idUsuario";
        $stmt = Flight::db()->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->execute();
        // fetchAll(), ya que puede devolver múltiples contactos
        $datos = $stmt->fetchAll();
    }

    Flight::json($datos);
});

Flight::route('POST /contactos', function () {
    $token = Flight::request()->getHeader('X-Token');
    $usuario = seleccionarUsuarioPorToken($token);

    // Si no existe token o no existe usuario
    if(!$token || !$usuario){
        // Devolvemos error 401 (Fallo en la autenticación)
        Flight::json(['error' => 'Error en la autenticación del usuario'], 401);
        Flight::stop();
        exit;
    }

    $nombre = Flight::request()->data->nombre;
    $telefono = Flight::request()->data->telefono;
    $email = Flight::request()->data->email;

    if(!nombreEsValido($nombre) || !telefonoEsValido($telefono) || !emailEsValido($email)){
        Flight::json(['error' => 'Error al guardar el contacto'], 400);
        Flight::stop();
        exit;
    }

    $idUsuario = $usuario['id'];
    $sql = "INSERT INTO contactos(nombre, telefono, email, usuario_id) VALUES (?, ?, ?, ?)";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1, $nombre);
    $stmt->bindParam(2, $telefono);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $idUsuario);
    $stmt->execute();

    Flight::json("El contacto $nombre ha sido guardado correctamente.");
});

Flight::route('PUT /contactos', function() {
    $token = Flight::request()->getHeader('X-Token');
    $usuario = seleccionarUsuarioPorToken($token);
    $idUsuario = $usuario['id'];

    // Si no existe token o no existe usuario
    if(!$token || !$usuario){
        // Devolvemos error 401 (Fallo en la autenticación)
        Flight::json(['error' => 'Error en la autenticación del usuario'], 401);
        Flight::stop();
        exit;
    }
    
    $idContacto = Flight::request()->data->id;
    // Verificamos que contacto existe en BD. Si existe nos devuelve array con sus datos
    $contactoExiste = verificarContacto($idContacto);

    if(!$contactoExiste){
        // Devolvemos error 404 (Contacto no existe)
        Flight::json(['error' => 'Error, el contacto no existe en la base de datos'], 404);
        Flight::stop();
        exit;
    }

    // Si el contacto no es del usuario devuelve error y sale de la función
    // Podríamos haber hecho esta comprobación como en DELETE, al final, pero es otra forma
    if($contactoExiste['usuario_id'] != $idUsuario){
        // Devolvemos error 403 (Usuario no tiene permiso sobre el contacto)
        Flight::json(['error' => 'No tienes permiso para eliminar este contacto.'], 403);
        Flight::stop();
        exit;
    }
    
    $nombre = Flight::request()->data->nombre;
    $telefono = Flight::request()->data->telefono;
    $email = Flight::request()->data->email;

    if(!nombreEsValido($nombre) || !telefonoEsValido($telefono) || !emailEsValido($email)){
        Flight::json(['error' => 'Error al guardar el contacto'], 400);
        Flight::stop();
        exit;
    }

    $sql = "UPDATE contactos SET nombre=?, telefono=?, email=? WHERE id = ? AND usuario_id = ?";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1, $nombre);
    $stmt->bindParam(2, $telefono);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $idContacto);
    $stmt->bindParam(5, $idUsuario);
    $stmt->execute();

    Flight::json("El contacto $nombre ha sido actualizado correctamente.");
});

Flight::route('DELETE /contactos', function() {
    $token = Flight::request()->getHeader('X-Token');
    $usuario = seleccionarUsuarioPorToken($token);

    // Si no existe token o no existe usuario
    if(!$token || !$usuario){
        // Devolvemos error 401 (Fallo en la autenticación)
        Flight::json(['error' => 'Error en la autenticación del usuario'], 401);
        Flight::stop();
        exit;
    }

    $idContacto = Flight::request()->data->id;
    // Verificamos que contacto existe en BD
    $contactoExiste = verificarContacto($idContacto);

    if(!$contactoExiste){
        // Devolvemos error 404 (Contacto no existe)
        Flight::json(['error' => 'Error, el contacto no existe en la base de datos'], 404);
        Flight::stop();
        exit;
    }

    $idUsuario = $usuario['id'];
    $sql = "DELETE FROM contactos WHERE id = ? AND usuario_id = ?";
    $stmt = Flight::db()->prepare($sql);
    $stmt->bindParam(1, $idContacto);
    $stmt->bindParam(2, $idUsuario);
    $stmt->execute();

    // Comprobamos si execute() afecta a alguna fila. Si no, es que el contacto no es del usuario
    if ($stmt->rowCount() > 0) {
        Flight::json("El contacto ha sido eliminado correctamente.");
    } else {
        // Devolvemos error 403 (Usuario no tiene permiso sobre el contacto)
        Flight::json(['error' => 'No tienes permiso para eliminar este contacto.'], 403);
    }

});

// No olvidarse de poner esto, si no no carga ningún servicio
Flight::start();

?>