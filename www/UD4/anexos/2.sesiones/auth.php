<?php
session_start();

function conecta()
{
    $servername ="db";
    $username = "root";
    $password = "test";  
    $dbname = "sesiones";
    try
    {
        $conPDO = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conPDO;
    }
    catch (PDOException $ex)
    {
        return $ex->getMessage();
    }
}

function comprobarUsuario($nombre, $pass, $conPDO)
{
    $consulta = "SELECT pass, rol FROM usuario WHERE nombre=:nombreTecleado";
    $stmt = $conPDO->prepare($consulta);
    try
    {
        $stmt->bindParam(':nombreTecleado', $nombre);
        $stmt->execute();

        //Si el usuario ya no existe, no valida
        if ($stmt->rowCount() != 1) return false;
        
        $fila=$stmt->fetch();
    
        $passBD=$fila['pass'];
        $rol = $fila['rol'];

        //Primero comprobamos que haya un usuario y después comprobamos la contraseña introducida
        if ($stmt->rowCount() == 1 && password_verify($pass, $passBD))
        {
            $usuario['nombre']=$nombre;
            $usuario['rol']=$rol;
            return $usuario;
        }
        else
        {
            return null;
        }
    }
    catch (PDOException $ex)
    {
        return $ex->getMessage();
    }
    finally
    {
        $conPDO = null;
        if ($stmt != null) $stmt->closeCursor();
        $stmt = null;
    }

}

//Comprobar si se reciben los datos
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $usuario = $_POST["usuario"];
    $pass = $_POST["pass"];

    if (empty($usuario) || empty($pass))
    {
        header('Location: login.php?error=true&message=Los campos del formulario son obligatorios.');
    }

    $conPDO = conecta();
    if (is_string($conPDO))
    {
        header('Location: login.php?error=true&message=' . $conPDO);
    }
    $user = comprobarUsuario($usuario, $pass, $conPDO);
    if(!$user)
    {
        header('Location: login.php?error=true');
    }
    elseif (is_string($user))
    {
        header('Location: login.php?error=true&message=' . $user);
    }
    else
    {
        $_SESSION['usuario'] = $user;
        //Redirigimos a index.php
        header('Location: index.php');
    }
}