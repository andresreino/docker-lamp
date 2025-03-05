<?php
include_once('../utils.php');
include_once('../usuarios/Usuario.php');
include_once('../ficheros/Fichero.php');
include_once('../tareas/Tarea.php');
include_once('../excepciones/DatabaseException.php');

// Conecta con la base de datos cuyo nombre se introduce por parámetro mediante PDO
function conectarDBPDO($nombreDB){
    // Creamos la conexión utilizando las variables de entorno del archivo .env
    $servername = $_ENV['DATABASE_HOST'];
    $username = $_ENV['DATABASE_USER'];
    $password = $_ENV['DATABASE_PASSWORD'];
    $dbname = $nombreDB;

    try {
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Forzar excepciones
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        // Si se produce error de conexión con BD salta este tipo de excepción
        // OJO, sólo las recogemos en métodos que tengan que ver con ficheros (pto 4 tarea UD5) y para que en demás métodos 
        // se recoja si falla conexión tenemos que cambiar excepción del catch de PDOException a Exception, ya que hereda de esta última
        throw new DatabaseException("Fallo al conectar con la base de datos.", "conectarDBPDO");
    }
}

// Guarda el usuario en la DB con los parámetros que se introducen (PDO)
function guardarUsuario($usuario){
    $username = $usuario->getUsername();
    $nombre = $usuario->getNombre();
    $apellidos = $usuario->getApellidos();
    $rol = ($usuario->getRol() === "usuario") ? 0 : 1;    
    $contrasena = $usuario->getContrasena();
    
    // En esta variable guardamos array con booleano true/false[0] + mensaje[1]
    $usuarioValido = usuarioEsValido($nombre, $apellidos, $username, $contrasena);
    
    if($usuarioValido[0]){
        try {
            $conexion = conectarDBPDO('tareas');
            //Consulta preparada
            $sql = "INSERT INTO usuarios (username, nombre, apellidos, rol, contrasena) VALUES (:username, :nombre, :apellidos, :rol, :contrasena)";
            $stmt = $conexion->prepare($sql);
                 
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
            // Creamos una contraseña encriptada con un HASH para guardarla en la BD
            $hasheado = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt->bindParam(':contrasena', $hasheado, PDO::PARAM_STR);
            
            $stmt->execute();
            // Devuelve la ejecución y mensaje si todo es correcto
            return [true, "Usuario guardado correctamente."];
        } catch(Exception $e) {
            return [false, "Error al guardar el usuario: " . $e->getMessage()];
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }
    }else{
        // Si usuario no valida todos los campo devuelve array con false[0] e info del error[1]
        return $usuarioValido;
    }
}

// Edita los datos de un usuario recibiendo un objeto Usuario por parámetro
function editarUsuario($usuario){
    $id = $usuario->getId();
    $username = $usuario->getUsername();
    $nombre = $usuario->getNombre();
    $apellidos = $usuario->getApellidos();
    $rol = ($usuario->getRol() === "usuario") ? 0 : 1;    
    $contrasena = $usuario->getContrasena();

    $datosAntiguosUsuario = buscarUsuario($id);
    $contrasenaAntigua = $datosAntiguosUsuario[1]->getContrasena();
    $hasheado = "";

    // Comprobamos si se ha modificado o no la antigua contraseña
    if($contrasena == "") {
        // Si no se modifica le damos este valor temporal para que pase la validación
        // De otro modo, el código hash no pasaría validación al no admitir símbolos
        $contrasena = "no modificada";
        $usuarioValido = usuarioEsValido($nombre, $apellidos, $username, $contrasena);
        $hasheado = $contrasenaAntigua;
    } else {
        // Si ha sido modificada, comprobamos que sea válida y la hasheamos
        $usuarioValido = usuarioEsValido($nombre, $apellidos, $username, $contrasena);
        $hasheado = password_hash($contrasena, PASSWORD_DEFAULT);
    }
    //$contrasena = ($contrasena == "") ? $contrasenaAntigua : $contrasena; 
    
    if($usuarioValido[0]){
        
        try {
            $conexion = conectarDBPDO('tareas');
            $sql = "UPDATE `usuarios` SET `username`='$username',`nombre`='$nombre',`apellidos`='$apellidos',`rol`=$rol,`contrasena`='$hasheado' WHERE id = " . $id;
            $stmt = $conexion->prepare($sql);

            $stmt->execute();
            // Devuelve la ejecución y mensaje si todo es correcto
            return [true, "Usuario actualizado correctamente."];
        } catch(Exception $e) {
            return [false, "Error al actualizar el usuario: " . $e->getMessage()];
        } finally {
            $conexion = null;
        }
    }else{
        // Si usuario no valida todos los campo devuelve array con false[0] e info del campo que da del error[1]
        return $usuarioValido;
    }        
}

// Devuelve la lista de usuarios existentes (objetos Usuario) en la DB (PDO)
function listarUsuariosPDO(){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT * FROM usuarios";

        $stmt = $conexion->prepare($sql);

        // FETCH_ASSOC Devuelve array indexado (nombre+campo tabla):[id] => 1 [nombre] => Juan 
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();

        $resultado = [];
        
        while($fila = $stmt->fetch()){
            $usuario = new Usuario('', '', '', '', '');
            $usuario->setId($fila['id']);
            $usuario->setUsername($fila['username']);
            $usuario->setNombre($fila['nombre']);
            $usuario->setApellidos($fila['apellidos']);
            $usuario->setContrasena($fila['contrasena']);
            $usuario->setRol($fila['rol']);

            $resultado[] = $usuario;
        }
        
        return [true, $resultado];

    } catch(Exception $e) {
        // Accedemos a la propiedad code del objeto PDOException para saber su código
        $error = $e->getCode();
        if($error == 1049){// Código 1049: base de datos no encontrada
            return [false, "No existe la base de datos."];
        }else{
            return [false, "Error al listar los usuarios de la base de datos: " . $e->getMessage()];
        }
    } finally {
        $conexion = null;
    }
}

// Busca el usuario con id introducido por parámetro (PDO)
function buscarUsuario($id){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT * FROM usuarios WHERE id = " . $id;
    
        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();
        // Sólo necesitamos una fila, usamos fetch() no fetchAll()
        $resultado = $stmt->fetch();
        // Creamos usuario indicando al constructor que inicialice con cadena vacía cada atributo
        $usuario = new Usuario('', '', '', '', '');
        $usuario->setId($resultado['id']);
        $usuario->setUsername($resultado['username']);
        $usuario->setNombre($resultado['nombre']);
        $usuario->setApellidos($resultado['apellidos']);
        $usuario->setContrasena($resultado['contrasena']);
        $usuario->setRol($resultado['rol']);
        
        return [true, $usuario];
    
    } catch(Exception $e) {
        return [false, "Error buscando el usuario en la base de datos: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}

// Busca el usuario con username introducido por parámetro (PDO)
function buscarUsuarioPorUsername($username){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT * FROM usuarios WHERE username ='" . $username . "'";
    
        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();
        // Sólo necesitamos una fila, usamos fetch() no fetchAll()
        $fila = $stmt->fetch();
        // Creamos usuario indicando al constructor que inicialice con cadena vacía cada atributo
        $usuario = new Usuario('', '', '', '', '');
        $usuario->setId($fila['id']);
        $usuario->setUsername($fila['username']);
        $usuario->setNombre($fila['nombre']);
        $usuario->setApellidos($fila['apellidos']);
        $usuario->setContrasena($fila['contrasena']);
        $usuario->setRol($fila['rol']);
        
        return [true, $usuario];
    
    } catch(Exception $e) {
        return [false, "Error buscando el usuario en la base de datos: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}

// Borra el usuario con id introducido por parámetro (PDO)
function borrarUsuario($id){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "DELETE FROM usuarios WHERE id = " . $id;
    
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return [true, "Usuario eliminado correctamente."];
    
    } catch(Exception $e) {
        return [false, "Error borrando el usuario en la base de datos: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}

// Busca las tareas de un usuario indicando por parámetro id y estado (opcional)(PDO)
function buscarTareasUsuario($id, $estado){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT t.*, u.username 
        FROM tareas t JOIN usuarios u 
        ON t.id_usuario = u.id 
        WHERE u.id = " . $id;
        // Si estado no está vacío le añade al WHERE otra condición
        if (!empty($estado)) {
            $sql .= " AND t.estado ='" . $estado . "'"; 
        }
    
        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();
        
        $resultado = [];
        // Mientras haya resultados al hacer el fetch
        while($fila = $stmt->fetch()){
            $tarea = new Tarea('', '', '', 0);
            $tarea->setId($fila['id']);
            $tarea->setTitulo($fila['titulo']);
            $tarea->setDescripcion($fila['descripcion']);
            $tarea->setEstado($fila['estado']);
            $tarea->setIdUsuario($fila['id_usuario']);

            $resultado[] = $tarea;
        };
        return [true, $resultado];
    
    } catch(Exception $e) {
        return [false, "Error buscando las tareas del usuario en la base de datos: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}

// Busca las tareas de un usuario indicando por parámetro username(PDO)
function buscarTareasUsuarioNoAdmin($username){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT t.*, u.username 
        FROM tareas t JOIN usuarios u 
        ON t.id_usuario = u.id 
        WHERE u.username ='" . $username . "'";
            
        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();

        $resultado = [];
        // Mientras haya resultados al hacer el fetch
        while($fila = $stmt->fetch()){
            $tarea = new Tarea('', '', '', 0);
            $tarea->setId($fila['id']);
            $tarea->setTitulo($fila['titulo']);
            $tarea->setDescripcion($fila['descripcion']);
            $tarea->setEstado($fila['estado']);
            $tarea->setIdUsuario($fila['id_usuario']);

            $resultado[] = $tarea;
        };
        return [true, $resultado];
    
    } catch(Exception $e) {
        return [false, "Error buscando las tareas del usuario en la base de datos: " . $e->getMessage()];
    } finally {
        $conexion = null;
    }
}
/*  ESTAS 4 FUNCIONES SE PASARON A LA CLASE FicherosDBImp PARA IMPLEMENTARLOS DESDE ALLÍ USANDO UNA INTERFACE
    (Yo los había hecho antes ya aquí. En la nueva clase son prácticamente iguales)

// Guarda los datos de un fichero en la BD introduciendo objeto Fichero por parámetro (PDO)
function guardarFicheroBD($fichero) {
    try {
        $conexion = conectarDBPDO('tareas');
        //Consulta preparada
        $sql = "INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (:nombre, :file, :descripcion, :id_tarea)";
        $stmt = $conexion->prepare($sql);

        $nombre = $fichero->getNombre();
        $file = $fichero->getFile();
        $descripcion = $fichero->getDescripcion();
        $idTarea = $fichero->getIdTarea();


        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':file', $file, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_tarea', $idTarea, PDO::PARAM_INT);
                
        $stmt->execute();
        // Devuelve la ejecución y mensaje si todo es correcto
        return [true, "Fichero guardado correctamente en la BD."];

    } catch(Exception $e) {
        if($e instanceof DatabaseException){
            return [false, "Error al guardar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod()];
        }
        return [false, "Error al guardar el fichero: " . $e->getMessage()];
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}


// Selecciona todos los ficheros que tiene una tarea y devuelve array con objetos Fichero
function listarFicherosTarea($idTarea){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT * FROM ficheros WHERE id_tarea =" . $idTarea;

        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();
        
        $resultado = [];
        
        while($fila = $stmt->fetch()){
            $fichero = new Fichero('', '', '', 0);
            $fichero->setId($fila['id']);
            $fichero->setNombre($fila['nombre']);
            $fichero->setFile($fila['file']);
            $fichero->setDescripcion($fila['descripcion']);
            $fichero->setIdTarea($fila['id_tarea']);

            $resultado[] = $fichero;
        }

        return [true, $resultado];

    } catch (Exception $e) {
        if($e instanceof DatabaseException){
            return [false, "Error al guardar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod()];
        }
        return [false, "Error listando los ficheros de la tarea: " . $e->getMessage()];
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

// Selecciona un fichero de la BD
function buscarFicheroDB($idFichero){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT * FROM ficheros WHERE id =" . $idFichero;

        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $stmt->execute();

        // Sólo necesitamos una fila, usamos fetch() no fetchAll()
        $fila = $stmt->fetch();
        // Creamos usuario indicando al constructor que inicialice con cadena vacía cada atributo o cero
        $fichero = new Fichero('', '', '', 0);
        $fichero->setId($fila['id']);
        $fichero->setNombre($fila['nombre']);
        $fichero->setFile($fila['file']);
        $fichero->setDescripcion($fila['descripcion']);
        $fichero->setIdTarea($fila['id_tarea']);

        return [true, $fichero];

    } catch (Exception $e) {
        if($e instanceof DatabaseException){
            return [false, "Error al guardar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod()];
        }
        return [false, "Error buscando el fichero por su id: " . $e->getMessage()];
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

// Elimina un fichero de la BD por su id
function borrarFicheroDB($idFichero){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "DELETE FROM ficheros WHERE id =" . $idFichero;
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return [true, "Fichero eliminado correctamente."];

    } catch (Exception $e) {
        if($e instanceof DatabaseException){
            return [false, "Error al guardar el fichero: " . $e->getMessage() . " Ha fallado el método " . $e->getMethod()];
        }
        return [false, "Error borrando el fichero: " . $e->getMessage()];
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}
*/
?>
