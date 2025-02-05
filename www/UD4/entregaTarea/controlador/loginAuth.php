<?php
session_start();
// Creamos sesión
include_once('../modelo/pdo.php');

function comprobarUsuario($usuario, $contrasena){
    try {
        $conexion = conectarDBPDO('tareas');
        $sql = "SELECT contrasena, rol FROM usuarios WHERE username=:nombreUsuario";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':nombreUsuario', $usuario);
        $stmt->execute();

        //Si no se obtiene un resultado no lo valida
        if ($stmt->rowCount() != 1) return [false, 1, "No existe el usuario"];
        
        $resultado = $stmt->fetch();
    
        $contrasenaBD = $resultado['contrasena'];
        $rol = $resultado['rol'];

        //Función compara contraseña que introduce usuario para login con el hash almacenaco en la BD
        if (password_verify($contrasena, $contrasenaBD)) {
            //Si es la misma, creamos array asociativo con los datos y lo devolvemos
            $user['username'] = $usuario;
            $user['rol'] = $rol;

            return [true, $user];
        } else {
            return [false, 2, "La contraseña es incorrecta."];
        }

    } catch (PDOException $e) {
        return [false, 3, $e->getMessage()];
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    if (empty($usuario) || empty($contrasena)) {
        header('Location: ../vista/login.php?error=true&message=Los campos del formulario son obligatorios.');
        // Si no ponemos este exit() puede no funcionar correctamente
        exit();
    }

    $resultado = comprobarUsuario($usuario, $contrasena);
    
    // Si resultado es false, recogeremos el mensaje de error del índice 1 de array que devuelve, dependiendo de si falla el usuario (1), la contraseña (2) o la conexión (3)
    if (!$resultado[0]) {
        switch($resultado[1]) {
            // En cada caso, se selecciona el mensaje contenido en índice 2 del array que devuelve la función
            case 1:
                header('Location: ../vista/login.php?error=true&message=' . $resultado[2]);
                break;
            case 2: 
                header('Location: ../vista/login.php?error=true&message=' . $resultado[2]);
                break;
            case 3:
                header('Location: ../vista/login.php?error=true&message=' . $resultado[2]);
                break;
            default:
            header('Location: ../vista/login.php?error=true&message=Ha ocurrido un error inesperado.');
        }
    } else {
        // Si $resultado[0] es true, guardamos en variable superglobal los datos del usuario, que se devuelven en índice 1 del array
        $_SESSION['usuario'] = $resultado[1];
        //Redirigimos a index.php si todo es correcto
        header('Location: ../index.php');
    }
}

?>